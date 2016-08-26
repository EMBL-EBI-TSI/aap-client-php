<?php

require '../vendor/autoload.php';
include 'Token/TokenFactory.php';
include 'Token/TokenPrinter.php';
include 'Token/TokenTester.php';
include 'Token/TokenUnserializer.php';

use Token\TokenFactory;
use Token\TokenPrinter;
use Token\TokenTester;
use Token\TokenUnserializer;

$print = false;
$tokens = [['Correct token',
           ['iat' => time(),
            'exp' => time() + 3600,
            'iss' => 'aap.ebi.ac.uk',
            'aud' => 'workbench.ebi.ac.uk',
            'sub' => 'psafont@ebi.ac.uk',
           ]
          ]];

$tokener      = new TokenFactory();
$unserializer = new TokenUnserializer();

foreach ($tokens as list($name, $claims))
{
  list($token, $signature_index) = $unserializer->getToken($tokener->createToken($claims));
  try
  {
    if ($print) {
      TokenPrinter::printToken($token);
    }

    TokenTester::testToken($token, $signature_index);

    echo '"' . $name . '" is' . chr(27) . '[92m A-OK' . chr(27) . '[0m' . PHP_EOL;
  } catch(Exception $e) {
    echo '"' . $name . '" is' . chr(27) . '[91m unacceptable' . chr(27) . '[0m: ' . $e->getMessage() . PHP_EOL;
  }
}

?>
