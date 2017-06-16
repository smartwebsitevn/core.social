<?php namespace App\Purse;

use Core\Support\Traits\ErrorLangTrait;

class Service
{
	use ErrorLangTrait;

	/**
	 * Lay duong dan file error lang
	 *
	 * @return string
	 */
	protected function getErrorLangPath()
	{
		return 'modules/purse/common';
	}
}