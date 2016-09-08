<?php

namespace Claim;

class ClaimFactory
{
	public function generateClaims() {
		$validityClaims = ClaimFactory::generateValidityClaims();
		# Since array_colum doesn't let us keep the key, we have
		# to use this convoluted way to get it back.
		return array_filter(array_combine(
		           array_keys($validityClaims),
		           array_column($validityClaims, 0)
		       ));
	}
	public function generateValidityClaims() {
		$claims = [];

		$canonic = ['iat' => time(),
		    'exp' => time() + 3600,
		    'iss' => 'aap.ebi.ac.uk',
		    'aud' => 'workbench.ebi.ac.uk',
		    'sub' => 'test',
		];

		$claims['Good token']             = array($canonic, true);

		$tmp = $canonic;
		$tmp['iat'] = time() - 3600;
		$tmp['exp'] = time() - 1;
		$claims['Expired token']          = array($tmp, false);

		$tmp = $canonic;
		unset($tmp['iat']);
		$claims['No issue time token']    = array($tmp, false);

		$tmp = $canonic;
		$tmp['iat'] = time() + 3600;
		$tmp['exp'] = time() + 3601;
		$claims['Too early token']        = array($tmp, false);

		$tmp = $canonic;
		unset($tmp['exp']);
		$claims['No expiration token']    = array($tmp, false);

		$tmp = $canonic;
		$tmp['iss'] = 'tsi.ebi.ac.uk';
		$claims['Untrusted issuer token'] = array($tmp, true);

		$tmp = $canonic;
		unset($tmp['iss']);
		$claims['No issuer token']        = array($tmp, true);

		$tmp = $canonic;
		$tmp['aud'] = 'portal.ebi.ac.uk';
		$claims['Unknown audience token'] = array($tmp, false);

		$tmp = $canonic;
		unset($tmp['aud']);
		$claims['No aud token']           = array($tmp, false);

		$tmp = $canonic;
		unset($tmp['sub']);
		$claims['No subject token']       = array($tmp, false);

		return $claims;
	}
}

?>
