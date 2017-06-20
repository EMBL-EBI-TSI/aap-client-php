<?php

namespace AAP\Token;

use Jose\Loader;
use Jose\Factory\JWKFactory;

class TokenDeserializer
{
	private $key;
	private $loader;

	public function __construct($filename) {
		$this->key = JWKFactory::createFromCertificateFile(
			$filename,
			['use'=>'sig', 'alg'=>'RS256']
		);
		$this->loader = new Loader();
	}

	/**
	 * Deserializes a token, if it really was a token
	 * @return [JWT, int] JWT token and the signature that was verified with.
	 * @throws InvalidArgumentException if argument is not an deserializable token.
	 */
	public function getToken($serialized_token) {
		$token = $this->loader->loadAndVerifySignatureUsingKey(
			$serialized_token,
			$this->key,
			['RS256'],
			$signature_index
		);
		return [$token, $signature_index];
	}
}
