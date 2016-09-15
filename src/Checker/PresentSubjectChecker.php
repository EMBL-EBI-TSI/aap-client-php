<?php

namespace Workbench\Checker;

class PresentSubjectChecker extends PresentClaimChecker
{
	public function __construct()
	{
		parent::__construct('sub', 'subject');
	}
}
