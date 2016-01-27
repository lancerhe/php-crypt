<?php
namespace Crypt\Tests;

use Crypt\AES;

/**
 * Class AESTest
 *
 * @package Crypt\Tests
 * @author  Lancer He <lancer.he@gmail.com>
 */
class AESTest extends \PHPUnit_Framework_TestCase {

    public function setUp() {
        $this->_key = 'nh9a6d2b6s6g9ynh';
        $this->_iv  = 'ddky2235gee1g3mr';
    }

    /**
     * @test
     */
    public function encrypt() {
        $crypt   = new AES();
        $encrypt = $crypt->encrypt('my message', $this->_key, $this->_iv);
        $this->assertEquals('S5r5uy5zA7yTGIMj0rk68A==', $encrypt);
    }

    /**
     * @test
     */
    public function decrypt() {
        $crypt   = new AES();
        $decrypt = $crypt->decrypt('S5r5uy5zA7yTGIMj0rk68A==', $this->_key, $this->_iv);
        $this->assertEquals('my message', $decrypt);
    }
}