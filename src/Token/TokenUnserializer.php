<?php

namespace Token;

use Jose\Loader;
use Jose\Factory\JWKFactory;

class TokenUnserializer
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

?>
