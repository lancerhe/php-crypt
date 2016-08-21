<?php
namespace LancerHe\Crypt;

/**
 * Class AES
 *
 * @package LancerHe\Crypt
 * @author  Lancer He <lancer.he@gmail.com>
 */
class AES {
    /**
     * @desc cipher的值为密钥长度可以为128、192、256
     * @var  string
     */
    private $__cipher = MCRYPT_RIJNDAEL_128;
    /**
     * @desc 模式 CBC ECB
     * @var  string
     */
    private $__mode = MCRYPT_MODE_CBC;

    /**
     * @param  string $input 明文
     * @param  string $key   16个字符串
     * @param  string $iv    16个字符串
     * @return string
     */
    public function encrypt($input, $key, $iv) {
        return base64_encode(mcrypt_encrypt($this->__cipher, $key, $input, $this->__mode, $iv));
    }

    /**
     * @param  string $input 密文
     * @param  string $key   16个字符串
     * @param  string $iv    16个字符串
     * @return string
     */
    public function decrypt($input, $key, $iv) {
        $input = mcrypt_decrypt($this->__cipher, $key, base64_decode($input), $this->__mode, $iv);
        // @去掉加密后多余的字符串
        return rtrim($input, "\0");
    }
}