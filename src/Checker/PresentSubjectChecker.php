<?php

namespace Workbench\Checker;

use Assert\Assertion;

use Jose\Object\JWTInterface;
use Jose\Checker\ClaimCheckerInterface;

class PresentSubjectChecker implements ClaimCheckerInterface
{
	/**
	 * {@inheritdoc}
	 */
	public function checkClaim(JWTInterface $jwt)
	{
		Assertion::true($jwt->hasClaim('sub'), sprintf('Lack of subject claim found.'));

		return ['sub'];
	}
}
