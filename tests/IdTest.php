<?php
namespace Crypt\Tests;

use Crypt\Id;

/**
 * Class IdTest
 *
 * @package Crypt\Tests
 * @author  Lancer He <lancer.he@gmail.com>
 */
class IdTest extends \PHPUnit_Framework_TestCase {
    /**
     * @var Id
     */
    protected $_crypt;

    public function setUp() {
        $this->_crypt = new Id();
    }

    /**
     * @test
     */
    public function encrypt() {
        $encrypt = $this->_crypt->encrypt(23123123);
        $this->assertEquals('w6lt46urq', $encrypt);
    }

    /**
     * @test
     */
    public function decrypt() {
        $decrypt = $this->_crypt->decrypt('1awntdz3z');
        $this->assertEquals(23123124, $decrypt);
    }

    /**
     * @test
     */
    public function alphaIDEncrypt() {
        $encrypt = $this->_crypt->alphaID(124, false, 8);
        $this->assertEquals('qdaaaaab', $encrypt);
    }

    /**
     * @test
     */
    public function alphaIDDecrypt() {
        $encrypt = $this->_crypt->alphaID('xvjaaaab', true, 8);
        $this->assertEquals('12443', $encrypt);
    }
}