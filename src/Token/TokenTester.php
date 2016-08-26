<?php

namespace Token;

include __DIR__ . '/../Checker/HardenedIssuerChecker.php';

use Jose\Loader;
use Jose\Factory\CheckerManagerFactory;
use Jose\Factory\JWKFactory;
use Jose\Checker\AudienceChecker;
use Checker\HardenedIssuerChecker;

class TokenTester
{
  private $key;

  public function __construct()
  {
    $this->key = JWKFactory::createFromCertificateFile(
      __DIR__ . '/../../crypto_files/disposable.public.pem',
      ['use'=>'sig', 'alg'=>'RS256']
    );
  }

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

  public function testToken($token) {
    $loader = new Loader();

    $jws = $loader->loadAndVerifySignatureUsingKey(
      $token,
      $this->key,
      ['RS256'],
      $signature_index
    );

    $claims = array('iss', 'aud', 'sub', 'exp', 'iat', 'name', 'admin');

    foreach ($claims as $claim) {
      echo chr(9) . $claim . ': ' . ($jws->hasClaim($claim) ? $jws->getClaim($claim) : 'NIL') .  PHP_EOL;
    }

    $checkmate = CheckerManagerFactory::createClaimCheckerManager(
      TokenTester::getClaimChecks(),
      TokenTester::getHeaderChecks()
    );
    $checkmate->checkJWS($jws, $signature_index);

  }
}

?>
