<?php

namespace AAP\Token;

use Jose\Factory\CheckerManagerFactory;
use AAP\Checker\PresentClaimsChecker;

class TokenValidator
{
	private $claimChecks;

	public function __construct(array $claimChecks)
	{

		$this->claimChecks = array_merge(
		       self::getMinimalClaimChecks(),
		       $claimChecks);
	}

	private static function getMinimalClaimChecks()
	{
		return [
		    'iat',
		    'exp',
		    new PresentClaimsChecker(['sub', 'exp', 'iat', 'email', 'name', 'nickname']),
		];
	}

	private static function getHeaderChecks()
	{
		return [ 'crit' ];
	}

	public function validate($token, $signature_index) {
		$checkmate = CheckerManagerFactory::createClaimCheckerManager(
		    $this->claimChecks,
		    self::getHeaderChecks()
		);
		$checkmate->checkJWS($token, $signature_index);
	}
}
