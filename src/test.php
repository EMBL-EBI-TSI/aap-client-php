<?php

require __DIR__ . '/../vendor/autoload.php';

include 'Claim/ClaimFactory.php';
include 'Token/TokenFactory.php';
include 'Token/TokenPrinter.php';
include 'Token/TokenUnserializer.php';
include 'Token/TokenValidator.php';

use Workbench\Claim\ClaimFactory;
use Workbench\Token\TokenFactory;
use Workbench\Token\TokenPrinter;
use Workbench\Token\TokenUnserializer;
use Workbench\Token\TokenValidator;

use PHPUnit\Framework\TestCase;

class TokenTest extends TestCase
{
	private static $tokener;
	private static $unserializer;

	public function setUp()
	{
		self::$tokener = new TokenFactory(
			__DIR__ . '/../crypto_files/disposable.private.pem'
		);
		self::$unserializer = new TokenUnserializer(
			__DIR__ . '/../crypto_files/disposable.public.pem'
		);
	}

	public function testClaims()
	{
		foreach (ClaimFactory::generateValidityClaims() as $name => list($claims, $expected))
		{
			$result = false;
			list($token, $signature_index) = self::$unserializer->getToken(
			                                 self::$tokener->createToken($claims));

			try
			{
				TokenValidator::validate($token, $signature_index);
				$result = true;
			} catch(Exception $e) {
			} finally
			{
				$this->assertEquals($result, $expected);
			}
		}
	}
}
