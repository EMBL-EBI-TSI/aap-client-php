<?php

namespace Workbench\Token;

class TokenPrinter {
	public static function print($name, $token) {
		$claims = array('iss', 'aud', 'sub', 'exp', 'iat', 'name', 'admin');

		echo '"' . $name . '":' . PHP_EOL;
		foreach ($claims as $claim) {
			echo chr(9) . $claim . ': ' . ($token->hasClaim($claim) ? $token->getClaim($claim) : 'NIL') .  PHP_EOL;
		}
	}
}
