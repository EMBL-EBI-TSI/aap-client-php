<?php

require __DIR__ . '/../vendor/autoload.php';

use AAP\Data\PayloadFactory;
use AAP\Token\TokenFactory;
use AAP\Token\TokenDeserializer;
use AAP\Token\TokenValidator;

use Jose\Checker\AudienceChecker;

use PHPUnit\Framework\TestCase;

class TokenTest extends TestCase
{
	protected static $validator;

	public function setUp()
	{
		self::$validator = new TokenValidator([new AudienceChecker('webapp.ebi.ac.uk')]);
	}

	/**
	 * @dataProvider tokenProvider
	 */
	public function testPayloads($token, $signature_index, $valid)
	{
		if (!$valid)
		{
			$this->expectException(Assert\InvalidArgumentException::class);
		}

		self::$validator->validate($token, $signature_index);

		$this->assertTrue(True, 'https://github.com/sebastianbergmann/phpunit-documentation/issues/171');
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
		foreach (PayloadFactory::generatePayloadValidities() as $name => list($payload, $valid))
		{
			list($token, $signature_index) = $deserializer->getToken(
			    $tokener->createToken($payload));
			$tokens[$name] = [$token, $signature_index, $valid];
		}
		return $tokens;
	}
}
