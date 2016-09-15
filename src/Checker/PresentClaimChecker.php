<?php

namespace Workbench\Checker;

use Assert\Assertion;

use Jose\Object\JWTInterface;
use Jose\Checker\ClaimCheckerInterface;

abstract class PresentClaimChecker implements ClaimCheckerInterface
{
	private $claim;
	private $name;

	protected function __construct(string $claim, string $name)
	{
		$this->claim = $claim;
		$this->name = $name;
	}

	/**
	 * {@inheritdoc}
	 */
	public final function checkClaim(JWTInterface $jwt)
	{
		Assertion::true($jwt->hasClaim($this->claim),
						sprintf('Lack of ' . $this->name . ' claim found.'));

		return [$this->claim];
	}
}
