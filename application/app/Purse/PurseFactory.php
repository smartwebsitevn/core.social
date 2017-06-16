<?php namespace App\Purse;

use App\Purse\Service\PurseService;

class PurseFactory extends \Core\Base\Factory
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
	 * Lay doi tuong PurseService
	 *
	 * @return PurseService
	 */
	public static function purse()
	{
		return static::makeService('PurseService');
	}

}