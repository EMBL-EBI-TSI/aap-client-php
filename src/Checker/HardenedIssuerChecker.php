<?php

namespace Checker;

use Assert\Assertion;
use Jose\Object\JWTInterface;
use Jose\Checker\ClaimCheckerInterface;

class HardenedIssuerChecker implements ClaimCheckerInterface
{
  /**
   * @var array
   */
  private $allowedIssuers = [];

  /**
   * HardenedIssuerChecker constructor.
   *
   * @param array $allowedIssuers
   */
  public function __construct($allowedIssuers)
  {
    $this->allowedIssuers = $allowedIssuers;
  }
  /**
   * {@inheritdoc}
   */
  public function checkClaim(JWTInterface $jwt)
  {
    Assertion::true($jwt->hasClaim('iss'), sprintf('Lack of issuer claim found.'));

    $issuer = $jwt->getClaim('iss');
    Assertion::true($this->isIssuerAllowed($issuer), sprintf('The issuer "%s" is not allowed.', $issuer));

    return ['iss'];
  }

  /**
   * @param string $issuer
   *
   * @return bool
   */
  protected function isIssuerAllowed($issuer) {
    return in_array($issuer, $this->allowedIssuers, true);
  }
}

