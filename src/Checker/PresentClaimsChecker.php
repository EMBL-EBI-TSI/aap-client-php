<?php

namespace Workbench\Checker;

use Assert\Assertion;

use Jose\Object\JWTInterface;
use Jose\Checker\ClaimCheckerInterface;

class PresentClaimsChecker implements ClaimCheckerInterface
{
	private $claims;

	public function __construct(array $claims)
	{
		$this->claims = $claims;
	}

	/**
	 * {@inheritdoc}
	 */
	public final function checkClaim(JWTInterface $jwt)
	{
		foreach ($this->claims as $claim)
		{
			Assertion::true($jwt->hasClaim($claim),
				sprintf('A disturbing lack of \'' . $claim . '\' claim was found.'));
		}
		return $this->claims;
	}
}
