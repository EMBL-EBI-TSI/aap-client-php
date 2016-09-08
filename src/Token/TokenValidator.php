<?php

namespace Workbench\Token;

use Jose\Factory\CheckerManagerFactory;
use Jose\Checker\AudienceChecker;
use Workbench\Checker\PresentSubjectChecker;
use Workbench\Checker\PresentIssueTimeChecker;

class TokenValidator
{
	private function getClaimChecks()
	{
		return [
			'exp',
			'iat',
			new AudienceChecker('workbench.ebi.ac.uk'),
			new PresentSubjectChecker(),
			new PresentIssueTimeChecker()
		];
	}

	private function getHeaderChecks()
	{
		return [ 'crit' ];
	}

	public function validate($token, $signature_index) {
		$checkmate = CheckerManagerFactory::createClaimCheckerManager(
			TokenValidator::getClaimChecks(),
			TokenValidator::getHeaderChecks()
		);
		$checkmate->checkJWS($token, $signature_index);
	}
}
