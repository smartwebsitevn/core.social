<?php namespace App\Withdraw;

use App\Withdraw\Service\WithdrawService;

class WithdrawFactory extends \Core\Base\Factory
{
	/**
	 * Lay namespace root
	 *
	 * @return string
	 */
	public function getRootNamespace()
	{
		return __NAMESPACE__;
	}

	/**
	 * Lay doi tuong WithdrawService
	 *
	 * @return WithdrawService
	 */
	public static function withdraw()
	{
		return static::makeService('WithdrawService');
	}
}