<?php

namespace Workbench\Data;

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
			'No audience token' => [
				[
					'aud' => NULL
				], true
			],
			'No subject token' => [
				[
					'sub' => NULL
				], false
			]
		];
	}

	private static function getFirst($item) {
		return $item[0];
	}

	public static function generateSampleClaims() {
		return ['iat'   => time(),
		        'exp'   => time() + 3600,
		        'iss'   => 'aap.ebi.ac.uk',
		        'aud'   => 'webapp.ebi.ac.uk',
		        'sub'   => 'subject',
		        'email' => 'subject@ebi.ac.uk',
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
