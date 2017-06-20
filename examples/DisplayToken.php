<?php
/*
 * This example is used to manually check tokens produced by the dev instance
 * of the AAP.
 */
require __DIR__ . '/../vendor/autoload.php';

use AAP\Token\TokenPrinter;
use AAP\Token\TokenDeserializer;
use AAP\Token\TokenValidator;

$deserializer = new TokenDeserializer(
	__DIR__ . '/../../dev.sso.aap.pem'
);
$validator = new TokenValidator([]);

$esc   = chr(27);
$print = true;

try
{
	list($token, $signature_index) =
		$deserializer->getToken($argv[1]);

	if ($print)
	{
		TokenPrinter::print("token", $token);
	}

	echo '"' . str_pad("token" . '" ', 5) . 'is' . $esc;

	$validator->validate($token, $signature_index);
	echo '[92m A-OK' . $esc . '[0m';
} catch (Exception $e) {
	echo '"' . str_pad("token" . '" ', 5) . 'is' . $esc;
	echo '[91m unacceptable' . $esc . '[0m: ' . $e->getMessage();
} finally
{
	echo PHP_EOL . ($print ? PHP_EOL : '');
}
