<?php

require __DIR__ . '/../vendor/autoload.php';

use Workbench\Data\ClaimFactory;
use Workbench\Token\TokenFactory;
use Workbench\Token\TokenPrinter;
use Workbench\Token\TokenUnserializer;
use Workbench\Token\TokenValidator;

use Jose\Checker\AudienceChecker;

$tokener = new TokenFactory(
	__DIR__ . '/../crypto_files/disposable.private.pem',
	'lalala' # keypass for the key, important not to use it in production :)
);
$unserializer = new TokenUnserializer(
	__DIR__ . '/../crypto_files/disposable.public.pem'
);
$validator = new TokenValidator([new AudienceChecker('webapp.ebi.ac.uk')]);

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

	echo '"' . str_pad($name . '" ', 46) . 'is' . $esc;

	try
	{
		$validator->validate($token, $signature_index);
		echo '[92m A-OK' . $esc . '[0m';
	} catch (Exception $e) {
		echo '[91m unacceptable' . $esc . '[0m: ' . $e->getMessage();
	} finally
	{
		echo PHP_EOL . ($print ? PHP_EOL : '');
	}
}
