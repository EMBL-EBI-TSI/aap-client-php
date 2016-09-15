<?php

namespace Workbench\Token;

class TokenPrinter {
	public static function print(
		$name,
		$token,
		$customClaims=['iss', 'aud', 'sub', 'exp', 'iat', 'email'])
	{
		$tokenClaims = array_keys($token->getClaims());
		$claims = array_unique(array_merge($customClaims, $tokenClaims));


		echo '"' . $name . '":' . PHP_EOL;
		foreach ($claims as $claim) {
			echo chr(9) . $claim . ': ' . ($token->hasClaim($claim) ? $token->getClaim($claim) : 'NIL') .  PHP_EOL;
		}
	}
}
