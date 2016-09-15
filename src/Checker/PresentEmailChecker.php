<?php

namespace Workbench\Checker;

class PresentEmailChecker extends PresentClaimChecker
{
	public function __construct()
	{
		parent::__construct('email', 'email');
	}
}
