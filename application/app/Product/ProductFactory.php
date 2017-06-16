<?php namespace App\Product;

use App\Product\Service\CatService;
use App\Product\Service\ProductService;
use App\Product\Service\OrderService;
use App\Product\Service\LogProviderRequestService;
use App\Product\Service\CustomerContactService;
use App\Product\Service\ShoppingService;
use App\Product\Library\ProviderManager;
use App\Product\Library\ProviderFactory;
use App\Product\Library\ProviderService;

class ProductFactory extends \Core\Base\Factory
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
	 * Lay doi tuong CatService
	 *
	 * @return CatService
	 */
	public static function cat()
	{
		return static::makeService('CatService');
	}

	/**
	 * Lay doi tuong ProductService
	 *
	 * @return ProductService
	 */
	public static function product()
	{
		return static::makeService('ProductService');
	}

	/**
	 * Lay doi tuong OrderService
	 *
	 * @return OrderService
	 */
	public static function order()
	{
		return static::makeService('OrderService');
	}

	/**
	 * Lay doi tuong LogProviderRequestService
	 *
	 * @return LogProviderRequestService
	 */
	public static function logProviderRequest()
	{
		return static::makeService('LogProviderRequestService');
	}

	/**
	 * Lay doi tuong CustomerContactService
	 *
	 * @return CustomerContactService
	 */
	public static function customerContact()
	{
		return static::makeService('CustomerContactService');
	}

	/**
	 * Lay doi tuong ShoppingService
	 *
	 * @return ShoppingService
	 */
	public static function shopping()
	{
		return static::makeService('ShoppingService');
	}

	/**
	 * Lay doi tuong ProviderManager
	 *
	 * @return ProviderManager
	 */
	public static function providerManager()
	{
		return static::makeServiceProvider('Library/ProviderManager');
	}

	/**
	 * Lay doi tuong ProviderFactory
	 *
	 * @param string $provider
	 * @return ProviderFactory
	 */
	public static function provider($provider)
	{
		return static::providerManager()->make($provider);
	}

	/**
	 * Lay doi tuong service cua provider
	 *
	 * @param string $provider
	 * @return ProviderService
	 */
	public static function providerService($provider)
	{
		return static::providerManager()->service($provider);
	}

}