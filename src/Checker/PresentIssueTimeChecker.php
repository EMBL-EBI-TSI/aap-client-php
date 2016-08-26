<?php

namespace Checker;

use Assert\Assertion;
use Jose\Object\JWTInterface;
use Jose\Checker\ClaimCheckerInterface;

class PresentIssueTimeChecker implements ClaimCheckerInterface
{
  /**
   * {@inheritdoc}
   */
  public function checkClaim(JWTInterface $jwt)
  {
    Assertion::true($jwt->hasClaim('iat'), sprintf('Lack of issued at time claim found.'));

    return ['iat'];
  }
}

