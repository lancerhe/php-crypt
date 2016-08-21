<?php
namespace LancerHe\Crypt;

/**
 * Class RSA
 *
 * @package LancerHe\Crypt
 * @author  Lancer He <lancer.he@gmail.com>
 */
Class RSA {
    /**
     * @var resource
     */
    private $__privateKey;
    /**
     * @var resource
     */
    private $__publicKey;
    /**
     * @var string
     */
    private $__keyPath = "./keys";

    /**
     * RSA constructor.
     *
     * @param string $key_path
     */
    public function __construct($key_path = '') {
        if ( $key_path )
            $this->__keyPath = $key_path;
        $this->__keyPath = rtrim($this->__keyPath, '/');
        if ( ! is_dir($this->__keyPath) )
            mkdir($this->__keyPath, 0755, true);
    }

    /**
     * 获取公钥模
     *
     * @return string
     */
    public function getPublicKeyModulus() {
        $cmd     = 'openssl rsa -in ' . $this->__keyPath . '/priv.key -noout -modulus';
        $modulus = exec($cmd, $res, $ret);
        $mod     = explode('=', $modulus);
        return empty($mod[1]) ? '' : $mod[1];
        //return "E563721B36A3F8F435C8967899387F36444B5821A803879C5E29BB3E806250B2203D7054A978E582D5EBA550FB4F14344D753159C9DBC1FE35331D1019317E8F";
    }

    /**
     * @desc  创建密钥对
     * @param int $len
     */
    public function createKey($len = 512) {
        $config = [
            "digest_alg"       => "sha1",
            "private_key_bits" => $len,
            "private_key_type" => OPENSSL_KEYTYPE_RSA,
            "encrypt_key"      => false,
        ];
        $r      = openssl_pkey_new($config);
        openssl_pkey_export($r, $priv_key);
        // File Output
        file_put_contents($this->__keyPath . DIRECTORY_SEPARATOR . 'priv.key', $priv_key);
        $this->__privateKey = openssl_pkey_get_public($priv_key);
        $rp                 = openssl_pkey_get_details($r);
        $pub_key            = $rp['key'];
        // File Output
        file_put_contents($this->__keyPath . DIRECTORY_SEPARATOR . 'pub.key', $pub_key);
        $this->__publicKey = openssl_pkey_get_public($pub_key);
    }

    /**
     * @desc   设置私钥
     * @return boolean
     */
    public function setupPrivateKey() {
        $file = $this->__keyPath . DIRECTORY_SEPARATOR . 'priv.key';
        if ( ! file_exists($file) ) {
            $this->createKey();
            return true;
        }
        $prk                = file_get_contents($file);
        $this->__privateKey = openssl_pkey_get_private($prk);
        return true;
    }

    /**
     * @desc   设置公钥
     * @return boolean
     */
    public function setupPublicKey() {
        $file = $this->__keyPath . DIRECTORY_SEPARATOR . 'pub.key';
        if ( ! file_exists($file) ) {
            $this->createKey();
            return true;
        }
        $puk               = file_get_contents($file);
        $this->__publicKey = openssl_pkey_get_public($puk);
        return true;
    }

    /**
     * 私钥解密
     *
     * @param $encrypted
     * @return null
     */
    public function privDecrypt($encrypted) {
        if ( ! is_string($encrypted) ) {
            return null;
        }
        $this->setupPrivateKey();
        $r = openssl_private_decrypt(base64_decode($encrypted), $decrypted, $this->__privateKey);
        if ( $r ) {
            return $decrypted;
        }
        return null;
    }

    /**
     * 公钥加密
     *
     * @param $data
     * @return null|string
     */
    public function pubEncrypt($data) {
        if ( ! is_string($data) ) {
            return null;
        }
        $this->setupPublicKey();
        $r = openssl_public_encrypt($data, $encrypted, $this->__publicKey);
        if ( $r ) {
            return base64_encode($encrypted);
        }
        return null;
    }


    // public function privEncrypt($data) {
    //     if(!is_string($data)) {
    //         return null;
    //     }
    //     $this->setupPrivateKey();
    //     $r = openssl_private_encrypt($data, $encrypted, $this->_private_key);
    //     if($r) {
    //         return $encrypted;
    //     }
    //     return null;
    // }
    // public function pubDecrypt($crypted) {
    //     if(!is_string($crypted)) {
    //         return null;
    //     }
    //     $this->setupPublicKey();
    //     $r = openssl_public_decrypt($crypted, $decrypted, $this->_public_key);
    //     if($r) {
    //         return $decrypted;
    //     }
    //     return null;
    // }
    /**
     *
     */
    public function __destruct() {
        @ fclose($this->__privateKey);
        @ fclose($this->__publicKey);
    }
}