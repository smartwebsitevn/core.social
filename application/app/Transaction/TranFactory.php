<?php namespace App\Transaction;

use App\Transaction\Service\TranService;

class TranFactory extends \Core\Base\Factory
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
	 * Lay doi tuong TranService
	 *
	 * @return TranService
	 */
	public static function tran()
	{
		return static::makeService('TranService');
	}

}