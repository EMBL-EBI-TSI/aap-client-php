<?php
/**
 * This example shows how to deserialize and validate tokens
 *
 * It also generates the tokens in order to be able to check them as if they
 * were produced by the AAP.
 */
require __DIR__ . '/../vendor/autoload.php';

use AAP\Data\ClaimFactory;
use AAP\Token\TokenFactory;
use AAP\Token\TokenPrinter;
use AAP\Token\TokenDeserializer;
use AAP\Token\TokenValidator;

use Jose\Checker\AudienceChecker;

$cryptofolder = __DIR__ . '/../crypto_files/';

$tokener = new TokenFactory(
	$cryptofolder . 'disposable.private.pem',
	'lalala' # keypass for the private pem, do not to it in production :)
);
$deserializer = new TokenDeserializer(
	$cryptofolder . 'disposable.public.pem'
);
$validator = new TokenValidator([new AudienceChecker('webapp.ebi.ac.uk')]);

$esc   = chr(27);
$print = false;

foreach (ClaimFactory::generateValidityClaims() as
	$name => list($claims, $expected))
{
	list($token, $signature_index) =
		$deserializer->getToken($tokener->createToken($claims));

	if ($print)
	{
		echo TokenPrinter::getPrettyPrinted($name, $token);
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
