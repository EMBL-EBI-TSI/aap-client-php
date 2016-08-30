<?php

require '../vendor/autoload.php';

include 'Claim/ClaimFactory.php';
include 'Token/TokenFactory.php';
include 'Token/TokenPrinter.php';
include 'Token/TokenUnserializer.php';
include 'Token/TokenValidator.php';

use Claim\ClaimFactory;
use Token\TokenFactory;
use Token\TokenPrinter;
use Token\TokenUnserializer;
use Token\TokenValidator;

$print  = false;
$esc    = chr(27);

$tokener      = new TokenFactory();
$unserializer = new TokenUnserializer();

foreach (ClaimFactory::generateClaims() as $name => $claims)
{
	$encoded_token = $tokener->createToken($claims);
	list($token, $signature_index) = $unserializer->getToken($encoded_token);
	try
	{
		if ($print) {
			echo $encoded_token . PHP_EOL;
			TokenPrinter::print($name, $token);
		}

		TokenValidator::validate($token, $signature_index);

		echo '"' . $name . '" is' . $esc . '[92m A-OK' . $esc . '[0m' . PHP_EOL;
	} catch(Exception $e) {
		echo '"' . $name . '" is' . $esc . '[91m unacceptable' . $esc . '[0m: ' . $e->getMessage() . PHP_EOL;
	}
	if ($print) {
		echo PHP_EOL;
	}
}

?>
