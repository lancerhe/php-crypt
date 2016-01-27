<?php
namespace Crypt\Tests;

use Crypt\AuthCode;

/**
 * Class AuthCodeTest
 *
 * @package Crypt\Tests
 * @author  Lancer He <lancer.he@gmail.com>
 */
class AuthCodeTest extends \PHPUnit_Framework_TestCase {
    /**
     * @test
     */
    public function encrypt_and_decrypt_expected_while_expiry_equal_zero() {
        $authCode  = new AuthCode("KEY_IS_ABC");
        $encrypted = $authCode->encrypt("VALUE_IS_BBQ");
        $decrypted = $authCode->decrypt($encrypted);
        $this->assertEquals("VALUE_IS_BBQ", $decrypted);
    }

    /**
     * @test
     */
    public function encrypt_and_decrypt_expected_while_expiry_not_equal_zero() {
        $authCode  = new AuthCode("KEY_IS_ABC");
        $authCode->setExpiry(10);
        $encrypted = $authCode->encrypt("VALUE_IS_BBQ");
        $decrypted = $authCode->decrypt($encrypted);
        $this->assertEquals("VALUE_IS_BBQ", $decrypted);
    }

    /**
     * @test
     */
    public function encrypt_and_decrypt_expected_while_expiry() {
        $authCode  = new AuthCode("KEY_IS_ABC");
        $authCode->setExpiry(-1);
        $encrypted = $authCode->encrypt("VALUE_IS_BBQ");

        $authCode  = new AuthCode("KEY_IS_ABC");
        $decrypted = $authCode->decrypt($encrypted);
        $this->assertEquals("", $decrypted);
    }}