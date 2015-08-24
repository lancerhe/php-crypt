<?php
/**
 * DES3 Library Test
 * @author Lancer He <lancer.he@gmail.com>
 * @since  2014-10-27
 */

namespace Crypt\Tests;

use Crypt\DES3;

class DES3Test extends \PHPUnit_Framework_TestCase {

    public function setUp() {
        $this->_key = '6d2b6s6g';
        $this->_iv  = '2235gee1';
    }

    /**
     * @test
     */
    public function encrypt() {
        $crypt   = new DES3();
        $encrypt = $crypt->encrypt('my message', $this->_key, $this->_iv);
        $this->assertEquals('JPZDDBXGOXZc949A+ggNlA==', $encrypt);
    }

    /**
     * @test
     */
    public function decrypt() {
        $crypt   = new DES3();
        $decrypt = $crypt->decrypt('JPZDDBXGOXZc949A+ggNlA==', $this->_key, $this->_iv);
        $this->assertEquals('my message', $decrypt);
    }

    /**
     * @test
     */
    public function decryptFailure() {
        $crypt   = new DES3();
        $decrypt = $crypt->decrypt('TESTTEST', $this->_key, $this->_iv);
        $this->assertEquals(false, $decrypt);
    }
}