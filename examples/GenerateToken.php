<?php

require __DIR__ . '/../vendor/autoload.php';

use AAP\Data\PayloadFactory;
use AAP\Token\TokenFactory;
use AAP\Token\TokenPrinter;
use AAP\Token\TokenDeserializer;

$cryptofolder = __DIR__ . '/../crypto_files/';

$tokener = new TokenFactory(
    $cryptofolder . 'disposable.private.pem',
    'lalala' # keypass for the private pem, do not to it in production :)
);
$deserializer = new TokenDeserializer(
    $cryptofolder . 'disposable.public.pem'
);

$claims = PayloadFactory::changePayload(
            PayloadFactory::generateSamplePayload(),
            [
                'email' => $argv[1]
            ]
        );

$token = $tokener->createToken($claims);
list($jwt, $signature_index) =
    $deserializer->getToken($token);

echo TokenPrinter::getPrettyPrinted('Token generated for ' . $argv[1], $jwt);
echo PHP_EOL . $token . PHP_EOL;
