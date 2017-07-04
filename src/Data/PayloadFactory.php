<?php

namespace AAP\Data;

class PayloadFactory
{
	/**
	 * Returns an array with name of a token, changes to be applied to the
	 * default token and whether or not it should be correct.
	 */
	private static function getPayloadChanges(){
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
			'Invalid expiration token' => [
				[
					'exp' => (time() + 3600) . 'a'
				], false
			],
			'Back to the future token' => [
				[
					'iat' => time() + 3600,
					'exp' => time() + 3601
				], true
			],
			'No issue time token' => [
				[
					'iat' => NULL
				], false
			],
			'Invalid issue time token' => [
				[
					'iat' => (time()) . 'a'
				], false
			],
			'Not redy yet token' => [
				[
					'nbf' => time() + 3600
				], false
			],
			'Invalid nbf token' => [
				[
					'nbf' => (time() - 1) . 'a'
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

	public static function generateSamplePayload() {
		return ['iat'      => time(),
		        'exp'      => time() + 3600,
		        'iss'      => 'aap.ebi.ac.uk',
		        'sub'      => 'usr-a1d0c6e83f027327d8461063f4ac58a6',
		        'email'    => 'subject@ebi.ac.uk',
		        'name'     => 'John Doe',
		        'nickname' => '73475cb40a568e8da8a045ced110137e159f890ac4da883b6b17dc651b3a8049',
		];
	}

	public static function changePayload($payload, $changes) {
		foreach ($changes as $key => $value) {
			if (is_null($value)) {
				unset($payload[$key]);
			} else {
				$payload[$key] = $value;
			}
		}
		return $payload;
	}

	public static function generatePayloads() {
		return array_map(PayloadFactory::getFirst(),
		    PayloadFactory::generatePayloadValidities());
	}

	public static function generatePayloadValidities() {
		$payloads = [];
		$changes = PayloadFactory::getPayloadChanges();

		foreach ($changes as $name => list($changes, $valid)) {
			$payloads[$name] = [
			    PayloadFactory::changePayload(
					PayloadFactory::generateSamplePayload(),
					$changes
				),
			    $valid
			];
		}

		return $payloads;
	}
}
