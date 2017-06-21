<?php

namespace AAP\Data;

class ClaimFactory
{
	/**
	 * Returns an array with name of a token, changes to be applied to the
	 * default token and whether or not it should be correct.
	 */
	private static function getClaimChanges(){
		return	[
			'There is absolutely no cause for alarm token' => [
				[], true
			],
			'Expired token' => [
				[
					'iat' => time() - 3600,
					'exp' => time() - 1
				], false
			],
			'No expiration token' => [
				[
					'exp' => NULL
				], false
			],
			'Back to the future token' => [
				[
					'iat' => time() + 3600,
					'exp' => time() + 3601
				], false
			],
			'No issue time token' => [
				[
					'iat' => NULL
				], false
			],
			'Untrusted issuer token' => [
				[
					'iss' => 'tsi.ebi.ac.uk'
				], true
			],
			'No issuer token' => [
				[
					'iss' => NULL
				], true
			],
			'Unknown audience token' => [
				[
					'aud' => 'portal.ebi.ac.uk',
				], false
			],
			'Known audience token' => [
				[
					'aud' => 'webapp.ebi.ac.uk'
				], true
			],
			'No subject token' => [
				[
					'sub' => NULL
				], false
			],
			'No email token' => [
				[
					'email' => NULL
				], false
			],
			'No name token' => [
				[
					'name' => NULL
				], false
			],
			'No nickname token' => [
				[
					'nickname' => NULL
				], false
			]
		];
	}

	private static function getFirst($item) {
		return $item[0];
	}

	public static function generateSampleClaims() {
		return ['iat'      => time(),
		        'exp'      => time() + 3600,
		        'iss'      => 'aap.ebi.ac.uk',
		        'sub'      => 'usr-a1d0c6e83f027327d8461063f4ac58a6',
		        'email'    => 'subject@ebi.ac.uk',
		        'name'     => 'John Doe',
		        'nickname' => '73475cb40a568e8da8a045ced110137e159f890ac4da883b6b17dc651b3a8049',
		];
	}

	public static function changeClaims($claims, $changes) {
		foreach ($changes as $key => $value) {
			if (is_null($value)) {
				unset($claims[$key]);
			} else {
				$claims[$key] = $value;
			}
		}
		return $claims;
	}

	public static function generateClaims() {
		return array_map(ClaimFactory::getFirst(),
		    ClaimFactory::generateValidityClaims());
	}

	public static function generateValidityClaims() {
		$claims = [];
		$changes = ClaimFactory::getClaimChanges();

		foreach ($changes as $name => list($changes, $valid)) {
			$claims[$name] = [
			    ClaimFactory::changeClaims(
					ClaimFactory::generateSampleClaims(),
					$changes
				),
			    $valid
			];
		}

		return $claims;
	}
}
