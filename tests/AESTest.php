<?php
namespace LancerHe\Crypt\Tests;

use LancerHe\Crypt\AES;

/**
 * Class AESTest
 *
 * @package LancerHe\Crypt\Tests
 * @author  Lancer He <lancer.he@gmail.com>
 */
class AESTest extends \PHPUnit_Framework_TestCase {
    /**
     * @var string
     */
    protected $_key;
    /**
     * @var string
     */
    protected $_iv;
    /**
     * @var AES
     */
    protected $_crypt;

    /**
     *
     */
    public function setUp() {
        $this->_key   = 'nh9a6d2b6s6g9ynh';
        $this->_iv    = 'ddky2235gee1g3mr';
        $this->_crypt = new AES();
    }

    /**
     * @test
     */
    public function encrypt() {
        $encrypt = $this->_crypt->encrypt('my message', $this->_key, $this->_iv);
        $this->assertEquals('S5r5uy5zA7yTGIMj0rk68A==', $encrypt);
    }

    /**
     * @test
     */
    public function decrypt() {
        $decrypt = $this->_crypt->decrypt('S5r5uy5zA7yTGIMj0rk68A==', $this->_key, $this->_iv);
        $this->assertEquals('my message', $decrypt);
    }
}