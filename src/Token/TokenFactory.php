<?php

namespace Workbench\Token;

use Jose\Factory\JWKFactory;
use Jose\Factory\JWSFactory;

final class TokenFactory {
	private $private_key;

	public function __construct($filename, $password) {
		$this->private_key = JWKFactory::createFromKeyFile(
			$filename,
			$password,
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
				'alg' => 'RS256',
			]
		);
	}
}
