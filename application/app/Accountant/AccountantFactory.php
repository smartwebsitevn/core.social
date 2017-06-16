<?php namespace App\Accountant;

use App\Accountant\Service\LedgerService;
use App\Accountant\Service\CashService;
use App\Accountant\Service\StockService;
use App\Accountant\Service\BalanceService;

class AccountantFactory extends \Core\Base\Factory
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
	 * Lay doi tuong LedgerService
	 *
	 * @return LedgerService
	 */
	public static function ledger()
	{
		return static::makeService('LedgerService');
	}

	/**
	 * Lay doi tuong CashService
	 *
	 * @return CashService
	 */
	public static function cash()
	{
		return static::makeService('CashService');
	}

	/**
	 * Lay doi tuong StockService
	 *
	 * @return StockService
	 */
	public static function stock()
	{
		return static::makeService('StockService');
	}

	/**
	 * Lay doi tuong BalanceService
	 *
	 * @return BalanceService
	 */
	public static function balance()
	{
		return static::makeService('BalanceService');
	}

}