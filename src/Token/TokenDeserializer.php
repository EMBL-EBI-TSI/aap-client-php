<?php

namespace AAP\Token;

use Jose\Loader;
use Jose\Factory\JWKFactory;
use Jose\Object\JWKSet;

class TokenDeserializer
{
    private $key_set;
    private $loader;

    /**
     * Constructor
     * param $filenames: string | array(string) one or more x509 certificates locations
     */
    public function __construct($filenames)
    {
        $this->key_set = new JWKSet();
        $this->loader = new Loader();

        if (is_string($filenames)) {
            $filenames = [$filenames];
        }

        foreach ($filenames as $filename) {
            $key = JWKFactory::createFromCertificateFile(
                $filename,
                ['use'=>'sig', 'alg'=>'RS256']
            );
            $this->key_set->addKey($key);
        }

    }

    /**
     * Deserializes a token, if it really was a token
     * @return [JWT, int] JWT token and the signature that was verified with.
     * @throws InvalidArgumentException if argument is not an deserializable token.
     */
    public function getToken($serializedToken)
    {
        $token = $this->loader->loadAndVerifySignatureUsingKeySet(
            $serializedToken,
            $this->key_set,
            ['RS256'],
            $signatureIndex
        );
        return [$token, $signatureIndex];
    }
}
