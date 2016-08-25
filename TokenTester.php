<?php

namespace Token;

require_once __DIR__ . '/vendor/autoload.php';

include 'HardenedIssuerChecker.php';

use Jose\Loader;
use Jose\Factory\CheckerManagerFactory;
use Jose\Checker\AudienceChecker;
use Jose\Checker\HardenedIssuerChecker;

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

  public function testToken($token, $key) {
    $loader = new Loader();
    try{
      $jws = $loader->loadAndVerifySignatureUsingKey(
        $token,
        $key,
        ['RS256'],
        $signature_index
      );

      $claims = array('iss', 'aud', 'sub', 'exp', 'iat', 'name', 'admin');
      foreach ($claims as $claim) {
        echo $claim . ': ' . ($jws->hasClaim($claim) ? $jws->getClaim($claim) : 'false') .  PHP_EOL;
      }
      $checkmate = CheckerManagerFactory::createClaimCheckerManager(
        TokenTester::getClaimChecks(),
        TokenTester::getHeaderChecks()
      );
      $checkmate->checkJWS($jws, $signature_index);

      echo 'JWT token is A-OK' . PHP_EOL;
    } catch(Exception $e) {
      echo 'JWT token not up to snuff: ' . $e->getMessage() . PHP_EOL;
    }
  }
}

?>
