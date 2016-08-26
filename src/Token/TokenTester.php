<?php

namespace Token;

include __DIR__ . '/../Checker/HardenedIssuerChecker.php';

use Jose\Factory\CheckerManagerFactory;
use Jose\Checker\AudienceChecker;
use Checker\HardenedIssuerChecker;

class TokenTester
{
  private function getClaimChecks()
  {
    return [
      'exp',
      'iat',
      new AudienceChecker('workbench.ebi.ac.uk'),
      new HardenedIssuerChecker(['aap.ebi.ac.uk'])
    ];
  }

  private function getHeaderChecks()
  {
    return [ 'crit' ];
  }

  public function testToken($token, $signature_index) {
    $checkmate = CheckerManagerFactory::createClaimCheckerManager(
      TokenTester::getClaimChecks(),
      TokenTester::getHeaderChecks()
    );
    $checkmate->checkJWS($token, $signature_index);
  }
}

?>
