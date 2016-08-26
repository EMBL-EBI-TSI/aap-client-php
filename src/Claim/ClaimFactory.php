<?php

namespace Claim;

class ClaimFactory
{
  public function generateClaims() {
    $claims = [];

    $canonic = ['iat' => time(),
                'exp' => time() + 3600,
                'iss' => 'aap.ebi.ac.uk',
                'aud' => 'workbench.ebi.ac.uk',
                'sub' => 'psafont@ebi.ac.uk',
               ];

    $claims['Good token']             = $canonic;

    $tmp = $canonic;
    $tmp['iat'] = time() - 3600;
    $tmp['exp'] = time() - 1;
    $claims['Expired token']          = $tmp;

    $tmp = $canonic;
    $tmp['iat'] = time() + 3600;
    $tmp['exp'] = time() - 3599;
    $claims['Too early token']        = $tmp;

    $tmp = $canonic;
    $tmp['iss'] = 'tsi.ebi.ac.uk';
    $claims['Untrusted issuer token'] = $tmp;

    $tmp = $canonic;
    $tmp['aud'] = 'portal.ebi.ac.uk';
    $claims['Untrusted issuer token'] = $tmp;

    $tmp = $canonic;
    unset($tmp['sub']);
    $claims['Subject missing token']  = $tmp;

    return $claims;
  }
}

?>
