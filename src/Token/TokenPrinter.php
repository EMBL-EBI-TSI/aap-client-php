<?php

namespace AAP\Token;

class TokenPrinter
{
    public static function getPrettyPrinted(
        $name,
        $token,
        $customClaims=['iss', 'aud', 'sub', 'exp', 'iat', 'email'])
    {
        $tokenClaims = array_keys($token->getClaims());
        $claims = array_unique(array_merge($customClaims, $tokenClaims));


        $prettyprint = '"' . $name . '":' . PHP_EOL;
        foreach ($claims as $claim) {
            $prettyprint .= chr(9) . $claim . ': ' . ($token->hasClaim($claim) ? $token->getClaim($claim) : 'NIL') .  PHP_EOL;
        }
        return $prettyprint;
    }
}
