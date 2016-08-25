<?php

require 'vendor/autoload.php';
include 'TokenFactory.php';
include 'TokenTester.php';

use Jose\Factory\JWKFactory;
use Token\TokenFactory;
use Token\TokenTester;

$public_key = JWKFactory::createFromCertificateFile(
  getcwd() . '/crypto_files/disposable.public.pem',
  ['use'=>'sig', 'alg'=>'RS256']
);

$tokener = new TokenFactory();
foreach($tokener->createTokens() as $token) {
  TokenTester::testToken($token, $public_key);
}

?>
