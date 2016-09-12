<?php

namespace Workbench\Claim;

class ClaimFactory
{
	private static function getClaimChanges(){
		return	[
			'Good token' => [
				[], true
			],
			'Expired token' => [
				[
					'iat' => time() - 3600,
					'exp' => time() - 1
				], false
			],
			'No issue time token' => [
				[
					'iat' => NULL
				], false
			],
			'Too early token' => [
				[
					'iat' => time() + 3600,
					'exp' => time() + 3601
				], false
			],
			'No expiration token' => [
				[
					'exp' => NULL
				], false
			],
			'Too early token' => [
				[
					'iat' => time() + 3600,
					'exp' => time() + 3601
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
				], false
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

	private static function generateSampleClaims() {
		return ['iat' => time(),
		    'exp' => time() + 3600,
		    'iss' => 'aap.ebi.ac.uk',
		    'aud' => 'workbench.ebi.ac.uk',
		    'sub' => 'subject',
		];
	}

	public static function changeClaims($claims, $changes) {
		foreach ($changes as $key => $value) {
			if (isset($value)) {
				unset($claims[$key]);
			}
			$claims[$key] = $value;
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
