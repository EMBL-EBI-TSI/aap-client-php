<?php

require '../vendor/autoload.php';

include 'Claim/ClaimFactory.php';
include 'Token/TokenFactory.php';
include 'Token/TokenPrinter.php';
include 'Token/TokenTester.php';
include 'Token/TokenUnserializer.php';

use Claim\ClaimFactory;
use Token\TokenFactory;
use Token\TokenPrinter;
use Token\TokenTester;
use Token\TokenUnserializer;

$print  = false;
$esc    = chr(27);

$tokener      = new TokenFactory();
$unserializer = new TokenUnserializer();

foreach (ClaimFactory::generateClaims() as $name => $claims)
{
  list($token, $signature_index) = $unserializer->getToken($tokener->createToken($claims));

  try
  {
    if ($print) {
      TokenPrinter::printToken($name, $token);
    }

    TokenTester::testToken($token, $signature_index);

    echo '"' . $name . '" is' . $esc . '[92m A-OK' . $esc . '[0m' . PHP_EOL;
  } catch(Exception $e) {
    echo '"' . $name . '" is' . $esc . '[91m unacceptable' . $esc . '[0m: ' . $e->getMessage() . PHP_EOL;
  }
  echo PHP_EOL;
}

?>
