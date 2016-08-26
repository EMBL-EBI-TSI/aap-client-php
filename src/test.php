<?php

require '../vendor/autoload.php';
include 'Token/TokenFactory.php';
include 'Token/TokenTester.php';

use Token\TokenFactory;
use Token\TokenTester;

$tokens = [['Correct token',
           ['iat' => time(),
            'exp' => time() + 3600,
            'iss' => 'aap.ebi.ac.uk',
            'aud' => 'workbench.ebi.ac.uk',
            'sub' => 'psafont@ebi.ac.uk',
           ]
          ]];

$tokener = new TokenFactory();
$probe   = new TokenTester();

foreach($tokens as list($name, $claims))
{
  $token = $tokener->createToken($claims);
  try
  {
    echo 'Testing "' . $name . '":' . PHP_EOL;

    $probe->testToken($token);

    echo '"' . $name . '" is' . chr(27) . '[92m A-OK' . chr(27) . '[0m' . PHP_EOL;
  } catch(Exception $e) {
    echo '"' . $name . '" is' . chr(27) . '[91m unacceptable' . chr(27) . '[0m: ' . $e->getMessage() . PHP_EOL;
  }
}

?>
