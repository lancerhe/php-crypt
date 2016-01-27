<?php
namespace Crypt;
/**
 * Class Id 短地址算法，将id数字转化定长字符串算法
 *
 * @package Crypt
 * @author  Lancer He <lancer.he@gmail.com>
 * @link    http://kvz.io/blog/2009/06/10/create-short-ids-with-php-like-youtube-or-tinyurl/
 */
class Id {
    /**
     * @desc 加密私钥
     * @var string
     */
    private $_key = 'ytthni';
    /**
     * @desc 首个数字保持在4以上加密结果为8位, 尾数加上偏移量以后的尾数必须保持一样, 也就是说偏移量的末尾必须为0
     * @var  array
     */
    private $_offset = [
        0 => 9597199012520,
        1 => 8356536645430,
        2 => 4939453366740,
        3 => 6114896633240,
        4 => 9568952145630,
        5 => 8998552522210,
        6 => 7833698581110,
        7 => 6959874663250,
        8 => 5676213335990,
        9 => 4596699874650,
    ];

    /**
     * encrypt 加密id
     *
     * @access public
     *
     * @param  int $id  未加密过的数字id
     * @return string   加密id结果
     */
    public function encrypt($id) {
        $id = intval($id) + $this->_offset[$this->_getNumberEnd($id)];
        return $this->alphaID($id, false, false, $this->_key);
    }

    /**
     * decrypt 解密id
     *
     * @access public
     *
     * @param  string $code  已加密过的字符串
     * @return int          id数字
     */
    public function decrypt($code) {
        $id = $this->alphaID($code, true, false, $this->_key);
        $id = $id - $this->_offset[$this->_getNumberEnd($id)];
        return $id;
    }

    /**
     * 获取数字的末位值
     *
     * @access public
     *
     * @param  int $number    需要加密的数字，如256
     * @return int            数字的末位值，如6
     */
    public function _getNumberEnd($number) {
        return substr(strval($number), - 1, 1);
    }

    /**
     * alphaID 转化id算法
     *
     * @access public
     *
     * @param mixed   $in       许加密或者解密的数据
     * @param boolean $to_num   是否转化为数字，true:比较解密, false:表示加密
     * @param mixed   $pad_up   是否转化为定长数字，如:6 ,加密结果一定为6位，false表示自动计算
     * @param mixed   $pass_key 组合加密私钥
     *
     * @return int            数字的末位值，如6
     */
    public function alphaID($in, $to_num = false, $pad_up = false, $pass_key = null) {
        $index = "abcdefghijklmnopqrstuvwxyz0123456789";
        if ( $pass_key !== null ) {
            // Although this function's purpose is to just make the
            // ID short - and not so much secure,
            // with this patch by Simon Franz (http://blog.snaky.org/)
            // you can optionally supply a password to make it harder
            // to calculate the corresponding numeric ID
            for ( $n = 0; $n < strlen($index); $n ++ ) {
                $i[] = substr($index, $n, 1);
            }
            $passhash = hash('sha256', $pass_key);
            $passhash = (strlen($passhash) < strlen($index))
                ? hash('sha512', $pass_key)
                : $passhash;
            for ( $n = 0; $n < strlen($index); $n ++ ) {
                $p[] = substr($passhash, $n, 1);
            }
            array_multisort($p, SORT_DESC, $i);
            $index = implode($i);
        }
        $base = strlen($index);
        if ( $to_num ) {
            // Digital number  <<--  alphabet letter code
            $in  = strrev($in);
            $out = 0;
            $len = strlen($in) - 1;
            for ( $t = 0; $t <= $len; $t ++ ) {
                $bcpow = bcpow($base, $len - $t);
                $out   = $out + strpos($index, substr($in, $t, 1)) * $bcpow;
            }
            if ( is_numeric($pad_up) ) {
                $pad_up --;
                if ( $pad_up > 0 ) {
                    $out -= pow($base, $pad_up);
                }
            }
            $out = sprintf('%F', $out);
            $out = substr($out, 0, strpos($out, '.'));
        } else {
            // Digital number  -->>  alphabet letter code
            if ( is_numeric($pad_up) ) {
                $pad_up --;
                if ( $pad_up > 0 ) {
                    $in += pow($base, $pad_up);
                }
            }
            $out = "";
            for ( $t = floor(log($in, $base)); $t >= 0; $t -- ) {
                $bcp = bcpow($base, $t);
                $a   = floor($in / $bcp) % $base;
                $out = $out . substr($index, $a, 1);
                $in  = $in - ($a * $bcp);
            }
            $out = strrev($out); // reverse
        }
        return $out;
    }
}