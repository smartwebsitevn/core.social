<?php namespace App\Invoice;

use App\Invoice\Service\InvoiceService;
use App\Invoice\Service\InvoiceOrderService;
use App\Invoice\Library\InvoiceService as InvoiceServiceDriver;
use App\Invoice\Library\InvoiceServiceManager;
use App\Invoice\Service\StatsService;

class InvoiceFactory extends \Core\Base\Factory
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
	 * Lay doi tuong InvoiceService
	 *
	 * @return InvoiceService
	 */
	public static function invoice()
	{
		return static::makeService('InvoiceService');
	}

	/**
	 * Lay doi tuong InvoiceOrderService
	 *
	 * @return InvoiceOrderService
	 */
	public static function invoiceOrder()
	{
		return static::makeService('InvoiceOrderService');
	}

	/**
	 * Lay doi tuong InvoiceServiceManager
	 *
	 * @return InvoiceServiceManager
	 */
	public static function invoiceServiceManager()
	{
		return static::makeServiceProvider('Library/InvoiceServiceManager');
	}

	/**
	 * Lay doi tuong InvoiceServiceDriver
	 *
	 * @param string $key
	 * @return InvoiceServiceDriver
	 */
	public static function invoiceService($key)
	{
		return static::invoiceServiceManager()->make($key);
	}

	/**
	 * Lay doi tuong StatsService
	 *
	 * @return StatsService
	 */
	public static function stats()
	{
		return static::makeService('StatsService');
	}
}