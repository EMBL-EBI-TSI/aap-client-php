<?php

namespace Workbench\Token;

use Jose\Factory\CheckerManagerFactory;
use Jose\Checker\AudienceChecker;
use Workbench\Checker\PresentSubjectChecker;
use Workbench\Checker\PresentIssueTimeChecker;
use Workbench\Checker\PresentEmailChecker;

class TokenValidator
{
	private static function getClaimChecks()
	{
		return [
			'exp',
			'iat',
			new AudienceChecker('workbench.ebi.ac.uk'),
			new PresentSubjectChecker(),
			new PresentIssueTimeChecker(),
			new PresentEmailChecker()
		];
	}

	private static function getHeaderChecks()
	{
		return [ 'crit' ];
	}

	public static function validate($token, $signature_index) {
		$checkmate = CheckerManagerFactory::createClaimCheckerManager(
			TokenValidator::getClaimChecks(),
			TokenValidator::getHeaderChecks()
		);
		$checkmate->checkJWS($token, $signature_index);
	}
}
