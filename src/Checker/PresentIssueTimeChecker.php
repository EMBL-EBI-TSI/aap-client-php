<?php

namespace Workbench\Checker;

class PresentIssueTimeChecker extends PresentClaimChecker
{
	public function __construct()
	{
		parent::__construct('iat', 'issued at time');
	}
}
