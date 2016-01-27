<?php
namespace Crypt;
/**
 * Class AuthCode
 *
 * @package Crypt
 * @author  Lancer He <lancer.he@gmail.com>
 */
class AuthCode {
    /**
     * @var string;
     */
    protected $_key;
    /**
     * @var int
     */
    protected $_expiry = 0;

    /**
     * AuthCode constructor.
     *
     * @param $key
     */
    public function __construct($key) {
        $this->_key = $key;
    }

    /**
     * @param $expiry
     */
    public function setExpiry($expiry) {
        $this->_expiry = intval($expiry);
    }

    /**
     * @param string $string
     * @return string
     */
    public function decrypt($string) {
        return $this->authCode($string, 'DECODE', $this->_key, $this->_expiry);
    }

    /**
     * @param string $string
     * @return string
     */
    public function encrypt($string) {
        return $this->authCode($string, 'ENCODE', $this->_key, $this->_expiry);
    }

    /**
     * @param        $string
     * @param string $operation DECODE  said plaintext ciphertext decryption key encryption  ,  said other
     * @param string $key
     * @param int    $expiry
     * @return string
     */
    public function authCode($string, $operation = 'DECODE', $key = '', $expiry = 0) {
        // 动态密匙长度，相同的明文会生成不同密文就是依靠动态密匙
        $dynamic_key_length = 4;
        // 密匙
        $key = md5($key ? $key : C('AU_KEY'));
        // 密匙a会参与加解密
        $key_a = md5(substr($key, 0, 16));
        // 密匙b会用来做数据完整性验证
        $key_b = md5(substr($key, 16, 16));
        // 密匙c用于变化生成的密文
        $key_c = $dynamic_key_length ? ($operation == 'DECODE' ? substr($string, 0, $dynamic_key_length) : substr(md5(microtime()), - $dynamic_key_length)) : '';
        // 参与运算的密匙
        $key_crypt  = $key_a . md5($key_a . $key_c);
        $key_length = strlen($key_crypt);
        // 明文，前10位用来保存时间戳，解密时验证数据有效性，10到26位用来保存$keyb(密匙b)，解密时会通过这个密匙验证数据完整性
        // 如果是解码的话，会从第$ckey_length位开始，因为密文前$ckey_length位保存 动态密匙，以保证解密正确
        $string        = $operation == 'DECODE' ? base64_decode(substr($string, $dynamic_key_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0) . substr(md5($string . $key_b), 0, 16) . $string;
        $string_length = strlen($string);
        $result        = '';
        $box           = range(0, 255);
        $rand_key      = [];
        // 产生密匙簿
        for ( $i = 0; $i <= 255; $i ++ ) {
            $rand_key[$i] = ord($key_crypt[$i % $key_length]);
        }
        // 用固定的算法，打乱密匙簿，增加随机性，好像很复杂，实际上对并不会增加密文的强度
        for ( $j = $i = 0; $i < 256; $i ++ ) {
            $j       = ($j + $box[$i] + $rand_key[$i]) % 256;
            $tmp     = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }
        // 核心加解密部分
        for ( $a = $j = $i = 0; $i < $string_length; $i ++ ) {
            $a       = ($a + 1) % 256;
            $j       = ($j + $box[$a]) % 256;
            $tmp     = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            // 从密匙簿得出密匙进行异或，再转成字符
            $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
        }
        if ( $operation == 'DECODE' ) {
            // substr($result, 0, 10) == 0 验证数据有效性
            // substr($result, 0, 10) - time() > 0 验证数据有效性
            // substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16) 验证数据完整性
            // 验证数据有效性，请看未加密明文的格式
            if ( (substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26) . $key_b), 0, 16) ) {
                return substr($result, 26);
            } else {
                return '';
            }
        } else {
            // 把动态密匙保存在密文里，这也是为什么同样的明文，生产不同密文后能解密的原因
            // 因为加密后的密文可能是一些特殊字符，复制过程可能会丢失，所以用base64编码
            return $key_c . str_replace('=', '', base64_encode($result));
        }
    }
}