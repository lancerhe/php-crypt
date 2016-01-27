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
     * @test
     */
    public function encrypt() {
        $crypt   = new Id();
        $encrypt = $crypt->encrypt(23123123);
        $this->assertEquals('w6lt46urq', $encrypt);
    }

    /**
     * @test
     */
    public function decrypt() {
        $crypt   = new Id();
        $decrypt = $crypt->decrypt('1awntdz3z');
        $this->assertEquals(23123124, $decrypt);
    }

    /**
     * @test
     */
    public function alphaIDEncrypt() {
        $crypt   = new Id();
        $encrypt = $crypt->alphaID(124, false, 8);
        $this->assertEquals('qdaaaaab', $encrypt);
    }


    /**
     * @test
     */
    public function alphaIDDecrypt() {
        $crypt   = new Id();
        $encrypt = $crypt->alphaID('xvjaaaab', true, 8);
        $this->assertEquals('12443', $encrypt);
    }
}