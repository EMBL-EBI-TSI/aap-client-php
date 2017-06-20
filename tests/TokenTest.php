<?php

require __DIR__ . '/../vendor/autoload.php';

use AAP\Data\ClaimFactory;
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
	public function testClaims($token, $signature_index, $expected)
	{
		$result = false;
		try
		{
			self::$validator->validate($token, $signature_index);
			$result = true;
		} catch(Exception $e) {
		} finally
		{
			$this->assertEquals($result, $expected);
		}
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
		foreach (ClaimFactory::generateValidityClaims() as $name => list($claims, $expected))
		{
			list($token, $signature_index) = $deserializer->getToken(
			    $tokener->createToken($claims));
			$tokens[$name] = [$token, $signature_index, $expected];
		}
		return $tokens;
	}
}
