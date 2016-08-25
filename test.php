<?php

require 'vendor/autoload.php';
include 'HardenedIssuerChecker.php';

use Jose\Factory\JWKFactory;
use Jose\Loader;
use Jose\Factory\CheckerManagerFactory;
use Jose\Checker\AudienceChecker;
use Jose\Checker\HardenedIssuerChecker;

$token = 'eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IlBhdSBSdWl6IGkgU2Fmb250IiwiYWRtaW4iOnRydWV9.O3cBw93cX-TTy9vS_Qd1i5HRuBe34SI8CzM5sQNXfuwi4Dcr2YRajozx2n1TBT22rnMuZfEPbzC4jqneB2D_Obpnf3qXl7cvXDHd3GxxatUgXdXHz_ngAi_sIED3c75VjBlmdJbsGSYCJoeS5lbglJu_U0HBeL4-L4SS_d-AAuAKuAq-3i2LgzR-bl7btAsI9XecGQrDCvwUGdrdPlrJJMm_-CocDcrIJjD8s1lvQ3iLxfsxZ7DNkUy0uUgrFxOHmsSS12Ot0VtyB4DJEBl3SAJY2yxIUvr1hrzrNzdthnMej0X8rYrUeSWUih4PcGzNopmcErZ-f50dD-eHVi7gyg';
$key_file = getcwd() . '/crypto_files/disposable.public.pem';
$claim_checks = [
  'exp',
  'iat',
  new AudienceChecker('workbench.ebi.ac.uk'),
  new HardenedIssuerChecker(['aap.ebi.ac.uk'])
];

$header_checks = [
  'crit'
];

$public_key = JWKFactory::createFromCertificateFile(
                  $key_file,
                  ['use'=>'sig', 'alg'=>'RS256']
              );
$loader = new Loader();
try{
  $jws = $loader->loadAndVerifySignatureUsingKey(
                  $token,
                  $public_key,
                  ['RS256'],
                  $signature_index
         );

  echo print_r($jws->getSignatures(), true) . PHP_EOL;

  $claims = array('iss', 'aud', 'sub', 'exp', 'iat', 'name', 'admin');
  foreach ($claims as $claim) {
    echo $claim . ': ' . ($jws->hasClaim($claim) ? $jws->getClaim($claim) : 'false') .  PHP_EOL;
  }
  $checkmate = CheckerManagerFactory::createClaimCheckerManager($claim_checks, $header_checks);
  $checkmate->checkJWS($jws, $signature_index);

  echo 'JWT token is A-OK' . PHP_EOL;
} catch(Exception $e) {
  echo 'JWT token not up to snuff: ' . $e->getMessage() . PHP_EOL;
}

?>
