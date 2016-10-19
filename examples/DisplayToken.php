<?php

require __DIR__ . '/../vendor/autoload.php';

use Workbench\Token\TokenPrinter;
use Workbench\Token\TokenUnserializer;
use Workbench\Token\TokenValidator;

$unserializer = new TokenUnserializer(
	__DIR__ . '/../../dev.sso.aap.pem'
);
$validator = new TokenValidator([]);

$esc   = chr(27);
$print = true;

try
{
	list($token, $signature_index) =
		$unserializer->getToken($argv[1]);

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
