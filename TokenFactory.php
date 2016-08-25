<?php

namespace Token;

require_once __DIR__ . '/vendor/autoload.php';

use Jose\Factory\JWKFactory;
use Jose\Factory\JWSFactory;

final class TokenFactory {
  private $private_key;

  public function __construct() {
    $this->private_key = JWKFactory::createFromKeyFile(
      getcwd() . '/crypto_files/disposable.private.pem',
      'lalala', # we don't really care if anybody steals this!
      [
        'use' => 'sig',
      ]
    );
  }
  public function createTokens() {
    $claims = [
      'iat' => time(),
      'exp' => time() + 3600,
      'iss' => 'aap.ebi.ac.uk',
      'aud' => 'workbench.ebi.ac.uk',
      'sub' => 'psafont@ebi.ac.uk',
    ];
    return [JWSFactory::createJWSToCompactJSON(
      $claims,
      $this->private_key,
      [
        'crit' => ['exp', 'aud'],
        'alg' => 'RS256',
      ]
    )];
  }
}
?>
