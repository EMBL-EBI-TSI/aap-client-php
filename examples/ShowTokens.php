<?php

require __DIR__ . '/../vendor/autoload.php';

use Workbench\Claim\ClaimFactory;
use Workbench\Token\TokenFactory;
use Workbench\Token\TokenPrinter;
use Workbench\Token\TokenUnserializer;
use Workbench\Token\TokenValidator;

$tokener = new TokenFactory(
	__DIR__ . '/../crypto_files/disposable.private.pem',
	'lalala' # keypass for the key, important not to use it in production :)
);
$unserializer = new TokenUnserializer(
	__DIR__ . '/../crypto_files/disposable.public.pem'
);

$esc   = chr(27);
$print = false;

foreach (ClaimFactory::generateValidityClaims() as
	$name => list($claims, $expected))
{
	list($token, $signature_index) =
		$unserializer->getToken($tokener->createToken($claims));

	if ($print)
	{
		TokenPrinter::print($name, $token);
	}

	echo '"' . $name . '" is' . $esc;

	try
	{
		TokenValidator::validate($token, $signature_index);
		echo '[92m A-OK' . $esc . '[0m';
	} catch (Exception $e) {
		echo '[91m unacceptable' . $esc . '[0m: ' . $e->getMessage();
	} finally
	{
		echo PHP_EOL . ($print ? PHP_EOL : '');
	}
}
