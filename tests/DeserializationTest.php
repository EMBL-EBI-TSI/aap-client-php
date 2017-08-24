<?php

require __DIR__ . '/../vendor/autoload.php';

use AAP\Data\PayloadFactory;
use AAP\Token\TokenFactory;
use AAP\Token\TokenDeserializer;
use AAP\Token\TokenValidator;

use Jose\Checker\AudienceChecker;

use PHPUnit\Framework\TestCase;

class DeserializationTest extends TestCase
{
    private $payload;

    public function setUp()
    {
        $this->payload = PayloadFactory::generateSamplePayload();
    }

    /**
     * @dataProvider deserializerProvider
     */
    public function testWith($token, $deserializer, $shouldDeserialize)
    {
        if (!$shouldDeserialize) {
            $this->expectException(InvalidArgumentException::class);
        }

        $deserializer->getToken($token);

        $this->assertTrue(true, 'https://github.com/sebastianbergmann/phpunit-documentation/issues/171');
    }

    public function deserializerProvider()
    {
        # build the objects needed for all test cases
        $cryptofolder = __DIR__ . '/../crypto_files/';

        $cryptonames = ['disposable', 'secondary', 'unusable'];
        $keyphrases = ['lalala', '', ''];
        $token_masters = [];

        for ($i = 0; $i < count($cryptonames); ++$i) {
            $name = $cryptonames[$i];

            $tokener = new TokenFactory(
                $cryptofolder . $name . '.private.pem',
                $keyphrases[$i]
            );
            $token = $tokener-> createToken($this->payload);
            $x509 = $cryptofolder . $name . '.public.pem';

            $token_masters[$name] = [$token, $x509];
        }

        # build test cases
        $case_data = [
            'Single Certificate' => [
                                     'disposable',
                                     ['disposable'],
                                     true],
            'Two Certificates' => [
                                   'secondary',
                                   ['disposable', 'secondary'],
                                   true],
            'Mismatching Certificate' => [
                                   'disposable',
                                   ['secondary'],
                                   false],
            'Mismatching Certificates' => [
                                   'unusable',
                                   ['disposable', 'secondary'],
                                   false]
        ];

        $test_cases = [];

        foreach ($case_data as $name => list($token_name, $cert_names, $valid)) {
            $certs = [];
            foreach ($cert_names as $cert_name) {
                $certs[] = $token_masters[$cert_name][1];
            }

            $deserializer = new TokenDeserializer($certs);
            $token = $token_masters[$token_name][0];
            $test_cases[$name] = [$token, $deserializer, $valid];
        }
        return $test_cases;
    }
}
