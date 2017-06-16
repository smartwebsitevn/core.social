<?php namespace App\ServiceOrder;

use App\ServiceOrder\Service\ServiceOrderService;

class ServiceOrderFactory extends \Core\Base\Factory
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
	 * Lay doi tuong ServiceOrderService
	 *
	 * @return ServiceOrderService
	 */
	public static function serivce_order()
	{
		return static::makeService('ServiceOrderService');
	}

}