<?php

/*
 * The MIT License (MIT)
 *
 * Copyright (c) 2014-2017 Spomky-Labs, EMBL-EBI
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE file for details.
 */

namespace AAP\Checker;

use Assert\Assertion;

use Jose\Object\JWTInterface;
use Jose\Checker\ClaimCheckerInterface;

class DateClaimsChecker implements ClaimCheckerInterface
{
	private $leeway;

	/**
	 * @param leeway in seconds
	 */
	public function __construct($leeway=0)
	{
		$this->leeway = $leeway;
	}

	/**
	 * {@inheritdoc}
	 */
	public function checkClaim(JWTInterface $jwt)
	{
		$claims = [];
		foreach (['exp', 'iat', 'nbf'] as $name)
		{
			if ($jwt->hasClaim($name)) {
				$claims[] = $name;

				$claim = $jwt->getClaim($name);
				Assertion::true(is_numeric($claim) ? intval($claim) == $claim : false,
								"'" . $name . "' claim must be a number");

				$claim = intval($claim);
				if ($name === 'exp')
				{
					Assertion::greaterThan($claim, time() + $this->leeway,
										   'The JWT has expired.');
				} elseif ($name === 'nbf')
				{
					Assertion::lessOrEqualThan($claim, time() - $this->leeway,
											   'The JWT cannot be used yet.');
				}
			}
		}
		return $claims;
	}
}

