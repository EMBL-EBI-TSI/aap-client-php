<?php

namespace AAP\Token;

use Jose\Factory\CheckerManagerFactory;
use AAP\Checker\PresentClaimsChecker;
use AAP\Checker\DateClaimsChecker;

class TokenValidator
{
    private $checkMate;

    public function __construct(array $claimChecks)
    {
        $claimChecks = array_merge(
               self::getMinimalClaimChecks(),
               $claimChecks
        );

        $this->checkMate = CheckerManagerFactory::createClaimCheckerManager(
            $claimChecks,
            self::getHeaderChecks()
        );
    }

    private static function getMinimalClaimChecks()
    {
        return [
            new DateClaimsChecker(),
            new PresentClaimsChecker(['sub', 'exp', 'iat', 'email', 'name', 'nickname']),
        ];
    }

    private static function getHeaderChecks()
    {
        return [ 'crit' ];
    }

    public function validate($token, $signatureIndex)
    {
        $this->checkMate->checkJWS($token, $signatureIndex);
    }
}
