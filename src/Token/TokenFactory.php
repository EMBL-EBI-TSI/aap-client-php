<?php

namespace AAP\Token;

use Jose\Factory\JWKFactory;
use Jose\Factory\JWSFactory;

final class TokenFactory
{
    private $privateKey;

    public function __construct($filename, $password)
    {
        $this->privateKey = JWKFactory::createFromKeyFile(
            $filename,
            $password,
            [
                'use' => 'sig',
            ]
        );
    }
    public function createToken($claims)
    {
        return JWSFactory::createJWSToCompactJSON(
            $claims,
            $this->privateKey,
            [
                'alg' => 'RS256',
            ]
        );
    }
}
