<?php

require __DIR__ . '/../vendor/autoload.php';

use AAP\Data\PayloadFactory;
use AAP\Token\TokenFactory;
use AAP\Token\TokenDeserializer;
use AAP\Token\TokenValidator;

use Jose\Checker\AudienceChecker;

use PHPUnit\Framework\TestCase;

class PayloadValidityTest extends TestCase
{
    protected static $validator;

    public function setUp()
    {
        self::$validator = new TokenValidator([new AudienceChecker('webapp.ebi.ac.uk')]);
    }

    /**
 	 * We are forced to validate tokens instead of payloads / claims because
 	 * of design decision in the JWT library.
     * @dataProvider tokenProvider
     */
    public function testPayload($token, $signatureIndex, $valid)
    {
        if (!$valid) {
            $this->expectException(Assert\InvalidArgumentException::class);
        }

        self::$validator->validate($token, $signatureIndex);

        $this->assertTrue(true, 'https://github.com/sebastianbergmann/phpunit-documentation/issues/171');
    }

    public function tokenProvider()
    {
        $cryptofolder = __DIR__ . '/../crypto_files/';

        $tokener = new TokenFactory(
            $cryptofolder . 'disposable.private.pem',
            'lalala'
        );
        $deserializer = new TokenDeserializer(
            $cryptofolder . 'disposable.public.pem'
        );

        $tokens = [];
        foreach (PayloadFactory::generatePayloadValidities() as $name => list($payload, $valid)) {
            list($token, $signatureIndex) = $deserializer->getToken(
                $tokener->createToken($payload));
            $tokens[$name] = [$token, $signatureIndex, $valid];
        }
        return $tokens;
    }
}
