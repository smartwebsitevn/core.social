<?php namespace App\Deposit;

use App\Deposit\Service\DepositService;

class DepositFactory extends \Core\Base\Factory
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
	 * Lay doi tuong DepositService
	 *
	 * @return DepositService
	 */
	public static function deposit()
	{
		return static::makeService('DepositService');
	}

}