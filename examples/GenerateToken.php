<?php

require __DIR__ . '/../vendor/autoload.php';

use Workbench\Data\ClaimFactory;
use Workbench\Token\TokenFactory;
use Workbench\Token\TokenPrinter;
use Workbench\Token\TokenUnserializer;

$tokener = new TokenFactory(
	__DIR__ . '/../crypto_files/disposable.private.pem',
	'lalala' # keypass for the key, important not to use it in production :)
);
$unserializer = new TokenUnserializer(
	__DIR__ . '/../crypto_files/disposable.public.pem'
);

$claims = ClaimFactory::changeClaims(
			ClaimFactory::generateSampleClaims(),
			[
				'email' => $argv[1],
				'aud' => 'workbench.ebi.ac.uk'
			]
		);

$token = $tokener->createToken($claims);
list($jwt, $signature_index) =
	$unserializer->getToken($token);

TokenPrinter::print('Token generated for ' . $argv[1], $jwt);
echo $token . PHP_EOL;
