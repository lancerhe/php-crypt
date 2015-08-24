<?php
/**
 * @desc Crypt_RSA加解密类
 */

namespace Crypt;

Class RSA {

    protected $_private_key;

    protected $_public_key;

    protected $_key_path = "/tmp/phprsa/";

    /**
     * @desc   设置密钥路径
     * @param  string $path
     * @throws Exception
     */
    public function __construct($key_path = '') {
        if ( $key_path ) 
            $this->_key_path = $key_path;

        $this->_key_path = rtrim($this->_key_path, '/');

        if ( ! is_dir($this->_key_path) ) 
            mkdir($this->_key_path, 0755, true);
    }

    /**
     * 获取公钥的modulus
     */
    public function getPublicKeyModulus() {
        $cmd     = 'openssl rsa -in '. $this->_key_path .'/priv.key -noout -modulus';
        $modulus = exec($cmd, $res, $ret);
        $mod     = explode('=', $modulus);
        return empty($mod[1]) ? '' : $mod[1];
        //return "E563721B36A3F8F435C8967899387F36444B5821A803879C5E29BB3E806250B2203D7054A978E582D5EBA550FB4F14344D753159C9DBC1FE35331D1019317E8F";
    }

    /**
     * @desc 创建密钥对
     */
    public function createKey($len=512) {
        $config = array(
            "digest_alg"       => "sha1",
            "private_key_bits" => $len,
            "private_key_type" => OPENSSL_KEYTYPE_RSA,
            "encrypt_key"      => false
        );
        $r = openssl_pkey_new($config);
        openssl_pkey_export($r, $priv_key);

        // File Output
        file_put_contents($this->_key_path . DIRECTORY_SEPARATOR . 'priv.key', $priv_key);
        $this->_private_key = openssl_pkey_get_public($priv_key);
        $rp = openssl_pkey_get_details($r);
        $pub_key = $rp['key'];

        // File Output
        file_put_contents($this->_key_path . DIRECTORY_SEPARATOR . 'pub.key', $pub_key);
        $this->_public_key = openssl_pkey_get_public($pub_key);
    }


    /**
     * @desc   设置私钥
     * @return boolean
     */
    public function setupPrivateKey() {
        $file = $this->_key_path . DIRECTORY_SEPARATOR . 'priv.key';
        if ( ! file_exists($file) ) {
            $this->createKey();
            return true;
        }
        $prk = file_get_contents($file);
        $this->_private_key = openssl_pkey_get_private($prk);
        return true;
    }

    /**
     * @desc   设置公钥
     * @return boolean
     */
    public function setupPublicKey() {
        $file = $this->_key_path . DIRECTORY_SEPARATOR . 'pub.key';
        if ( ! file_exists($file) ) {
            $this->createKey();
            return true;
        }
        $puk = file_get_contents($file);
        $this->_public_key = openssl_pkey_get_public($puk);
        return true;
    }

    /**
     * @desc   私钥加密
     * @param  string $data 
     * @return false binary
     */
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


    /**
     * @desc   私钥解密
     * @param  binary $encrypted 
     * @return false string
     */
    public function privDecrypt($encrypted) {
        if(!is_string($encrypted)) {
            return null;
        }
        $this->setupPrivateKey();
        
        $r = openssl_private_decrypt($encrypted, $decrypted, $this->_private_key);
        if($r) {
            return $decrypted;
        }
        return null;
    }


    /**
     * @desc   公钥加密
     * @param  string $data 
     * @return false binary
     */
    public function pubEncrypt($data) {
        if(!is_string($data)) {
            return null;
        }
        $this->setupPublicKey();
        
        $r = openssl_public_encrypt($data, $encrypted, $this->_public_key);
        if($r) {
            return $encrypted;
        }
        return null;
    }


    /**
     * @desc   公钥解密
     * @param  binary $crypted 
     * @return false string
     */
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


    public function __destruct() {
        @ fclose($this->_private_key);
        @ fclose($this->_public_key);
    }
}