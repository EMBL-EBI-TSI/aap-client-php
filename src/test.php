<?php

require '../vendor/autoload.php';
include 'Token/TokenFactory.php';
include 'Token/TokenTester.php';

use Jose\Factory\JWKFactory;
use Token\TokenFactory;
use Token\TokenTester;

$public_key = JWKFactory::createFromCertificateFile(
  __DIR__ . '/../crypto_files/disposable.public.pem',
  ['use'=>'sig', 'alg'=>'RS256']
);

$tokener = new TokenFactory();
foreach($tokener->createTokens() as list($name, $token)) {
  try
  {
    echo 'Testing "' . $name . '":' . PHP_EOL;
    TokenTester::testToken($token, $public_key);

    echo '"' . $name . '" is' . chr(27) . '[92m A-OK' . chr(27) . '[0m' . PHP_EOL;
  } catch(Exception $e) {
    echo '"' . $name . '" is' . chr(27) . '[91m unacceptable' . chr(27) . '[0m: ' . $e->getMessage() . PHP_EOL;
  }
}

?>
