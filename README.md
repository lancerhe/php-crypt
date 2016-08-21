PHP Crypt
============

[![Latest Stable Version](https://poser.pugx.org/lancerhe/php-crypt/v/stable)](https://packagist.org/packages/lancerhe/php-crypt) [![Total Downloads](https://poser.pugx.org/lancerhe/php-crypt/downloads)](https://packagist.org/packages/lancerhe/php-crypt) [![Latest Unstable Version](https://poser.pugx.org/lancerhe/php-crypt/v/unstable)](https://packagist.org/packages/lancerhe/php-crypt) [![License](https://poser.pugx.org/lancerhe/php-crypt/license)](https://packagist.org/packages/lancerhe/php-crypt)

Crypt for AES, RSA, 3DES and some special algorithms.

Requirements
------------

**PHP5.3.0 or later**

Installation
------------

Create or modify your composer.json

``` json
{
    "require": {
        "lancerhe/php-crypt": "1.1.0"
    }
}
```

Usage
-----

AES

``` php
<?php
require('./vendor/autoload.php');

$key     = 'nh9a6d2b6s6g9ynh';
$iv      = 'ddky2235gee1g3mr';
$source  = 'my message';
$crypt   = new \LancerHe\Crypt\AES();
$encrypt = $crypt->encrypt($source, $key, $iv); 
var_dump($encrypt);    // S5r5uy5zA7yTGIMj0rk68A==
$decrypt = $crypt->decrypt($encrypt, $key, $iv);
var_dump($source);     // my message
```

3DES

``` php
<?php
require('./vendor/autoload.php');

$key     = '6d2b6s6g';
$iv      = '2235gee1';
$source  = 'my message';
$crypt   = new \LancerHe\Crypt\TripleDES();
$encrypt = $crypt->encrypt($source, $key, $iv); 
var_dump($encrypt);    // JPZDDBXGOXZc949A+ggNlA==
$decrypt = $crypt->decrypt($encrypt, $key, $iv);
var_dump($source);     // my message
```

RSA

``` php
<?php
require('./vendor/autoload.php');

$crypt   = new \LancerHe\Crypt\RSA('/tmp/');
$encrypt = $crypt->pubEncrypt('new message');
var_dump($encrypt);  // rand base64_encode
$decrypt = $crypt->privDecrypt($encrypt);
var_dump($decrypt);  // new message
```

Id

``` php
<?php
require('./vendor/autoload.php');

$crypt   = new \LancerHe\Crypt\Id();
$encrypt = $crypt->encrypt(23123123);
var_dump($encrypt); // w6lt46urq
$decrypt = $crypt->decrypt($encrypt);
var_dump($encrypt); // 23123123
```