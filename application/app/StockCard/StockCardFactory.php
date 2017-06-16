<?php namespace App\StockCard;

use App\StockCard\Service\AuthCardService;
use App\StockCard\Service\StockCardService;

class StockCardFactory extends \Core\Base\Factory
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
	 * Lay doi tuong StockCardService
	 *
	 * @return StockCardService
	 */
	public static function card()
	{
		return static::makeService('StockCardService');
	}

	/**
	 * Lay doi tuong AuthCardService
	 *
	 * @return AuthCardService
	 */
	public static function auth()
	{
		return static::makeService('AuthCardService');
	}
}