<?php namespace App\Currency;

use App\Currency\Service\CurrencyService;

class CurrencyFactory extends \Core\Base\Factory
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
	 * Lay doi tuong service chinh
	 *
	 * @return Service
	 */
	public static function service()
	{
		return static::makeServiceProvider('Service');
	}

	/**
	 * Lay doi tuong CurrencyService
	 *
	 * @return CurrencyService
	 */
	public static function currency()
	{
		return static::makeService('CurrencyService');
	}

}