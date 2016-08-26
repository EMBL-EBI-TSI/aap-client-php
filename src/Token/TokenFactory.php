<?php

namespace Token;

use Jose\Factory\JWKFactory;
use Jose\Factory\JWSFactory;

final class TokenFactory {
  private $private_key;

  public function __construct() {
    $this->private_key = JWKFactory::createFromKeyFile(
      __DIR__ . '/../../crypto_files/disposable.private.pem',
      'lalala', # we don't really care if anybody steals this!
      [
        'use' => 'sig',
      ]
    );
  }
  public function createToken($claims) {
    return JWSFactory::createJWSToCompactJSON(
                       $claims,
                       $this->private_key,
                       [
                         'crit' => ['exp', 'aud'],
                         'alg' => 'RS256',
                       ]
    );
  }
}
?>
