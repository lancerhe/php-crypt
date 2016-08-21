<?php
namespace LancerHe\Crypt\Tests;

use LancerHe\Crypt\TripleDES;

/**
 * Class TripleDESTest
 *
 * @package LancerHe\Crypt\Tests
 * @author  Lancer He <lancer.he@gmail.com>
 */
class TripleDESTest extends \PHPUnit_Framework_TestCase {
    /**
     * @var string
     */
    protected $_key;
    /**
     * @var string
     */
    protected $_iv;
    /**
     * @var TripleDES
     */
    protected $_crypt;

    /**
     *
     */
    public function setUp() {
        $this->_key   = '6d2b6s6g';
        $this->_iv    = '2235gee1';
        $this->_crypt = new TripleDES();
    }

    /**
     * @test
     */
    public function encrypt_return_string() {
        $encrypt = $this->_crypt->encrypt('my message', $this->_key, $this->_iv);
        $this->assertEquals('JPZDDBXGOXZc949A+ggNlA==', $encrypt);
    }

    /**
     * @test
     */
    public function decrypt_success_return_correct_string() {
        $decrypt = $this->_crypt->decrypt('JPZDDBXGOXZc949A+ggNlA==', $this->_key, $this->_iv);
        $this->assertEquals('my message', $decrypt);
    }

    /**
     * @test
     */
    public function decrypt_failed_return_false() {
        $decrypt = $this->_crypt->decrypt('TESTTEST', $this->_key, $this->_iv);
        $this->assertEquals(false, $decrypt);
    }
}