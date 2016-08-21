<?php
namespace LancerHe\Crypt\Tests;

use LancerHe\Crypt\RSA;

/**
 * Class RSATest
 *
 * @package LancerHe\Crypt\Tests
 * @author  Lancer He <lancer.he@gmail.com>
 */
class RSATest extends \PHPUnit_Framework_TestCase {
    /**
     * @var RSA
     */
    public $crypt;

    /**
     *
     */
    public function setUp() {
        $this->crypt = new RSA('/tmp/');
    }

    /**
     * @test
     */
    public function createKey() {
        $this->crypt->createKey();
        $this->assertTrue(file_exists('/tmp/priv.key'));
        $this->assertTrue(file_exists('/tmp/pub.key'));
    }

    /**
     * @test
     */
    public function setupPrivateKey() {
        $setup_result = $this->crypt->setupPrivateKey();
        $this->assertTrue($setup_result);
        $key_exists_setup = $this->crypt->setupPrivateKey();
        $this->assertTrue($key_exists_setup);
    }

    /**
     * @test
     */
    public function setupPublicKey() {
        $setup_result = $this->crypt->setupPublicKey();
        $this->assertTrue($setup_result);
        $key_exists_setup = $this->crypt->setupPublicKey();
        $this->assertTrue($key_exists_setup);
    }

    /**
     * @test
     */
    public function getPublicKeyModulus() {
        file_put_contents('/tmp/priv.key', file_get_contents(dirname(__FILE__) . '/RSASetup/priv.key'));
        $modulus = $this->crypt->getPublicKeyModulus();
        $this->assertEquals(file_get_contents(dirname(__FILE__) . '/RSASetup/pubmodulus'), $modulus);
    }

    /**
     * @test
     */
    public function pubEncryptParamterNotString() {
        $encrypted = $this->crypt->pubEncrypt([]);
        $this->assertEquals(null, $encrypted);
    }

    /**
     * @test
     */
    public function privDecryptParamterNotString() {
        $decrypted = $this->crypt->privDecrypt([]);
        $this->assertEquals(null, $decrypted);
    }

    /**
     * @test
     */
    public function pubEncryptFailure() {
        $this->setupPublicKey();
        $encrypted = $this->crypt->pubEncrypt("0x45d267021a5117a22610953f3cf89b3bca9f9f378ebc757f2840331c0a867b7928a2ebc06c0");
        $this->assertEquals(null, $encrypted);
    }

    /**
     * @test
     */
    public function privDecryptFailure() {
        $this->setupPrivateKey();
        $decrypted = $this->crypt->privDecrypt("aassddttdd");
        $this->assertEquals(null, $decrypted);
    }

    /**
     * @test
     */
    public function pubEncryptAndPrivDecryptSuccess() {
        $encrypted = $this->crypt->pubEncrypt('new message');
        $this->assertEquals('new message', $this->crypt->privDecrypt($encrypted));
    }

    /**
     *
     */
    public function tearDown() {
        file_exists("/tmp/priv.key") && unlink("/tmp/priv.key");
        file_exists("/tmp/pub.key") && unlink("/tmp/pub.key");
        unset($this->crypt);
    }
}