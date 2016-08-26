<?php

namespace Token;

class TokenPrinter {
  public function printToken($token) {
    $claims = array('iss', 'aud', 'sub', 'exp', 'iat', 'name', 'admin');

    echo 'Testing "' . $name . '":' . PHP_EOL;
    foreach ($claims as $claim) {
      echo chr(9) . $claim . ': ' . ($token->hasClaim($claim) ? $token->getClaim($claim) : 'NIL') .  PHP_EOL;
    }
  }
}

?>
