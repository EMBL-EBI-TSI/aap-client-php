<?php
/*
 * This can be used to manually check encoded tokens.
 * Just pass the encoded token as the argument, like so:
 *     php DecodeToken.php encodedToken
 * Note: by default uses the disposable public pem to verify
 *   the signature of the token, change it if you need to
 *   very tokens signed by another private key.
 */
require __DIR__ . '/../vendor/autoload.php';

use AAP\Token\TokenPrinter;
use AAP\Token\TokenDeserializer;
use AAP\Token\TokenValidator;

$deserializer = new TokenDeserializer(
    __DIR__ . '/../crypto_files/disposable.public.pem'
);
$validator = new TokenValidator([]);

$esc   = chr(27);
$print = true;

try {
    $name = 'token';
    $serialized_token = $argv[1];

    list($token, $signature_index) =
        $deserializer->getToken($serialized_token);

    if ($print) {
        echo TokenPrinter::getPrettyPrinted($name, $token);
    }

    $validator->validate($token, $signature_index);

    echo '"' . str_pad($name . '" ', 5) . 'is' . $esc;
    echo '[92m A-OK' . $esc . '[0m';
} catch (Exception $e) {
    echo '"' . str_pad($name . '" ', 5) . 'is' . $esc;
    echo '[91m unacceptable' . $esc . '[0m: ' . $e->getMessage();
} finally {
    echo PHP_EOL . ($print ? PHP_EOL : '');
}
