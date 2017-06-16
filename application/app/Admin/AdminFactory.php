<?php namespace App\Admin;

use App\Admin\Service\AdminAuth;

class AdminFactory extends \Core\Base\Factory
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
	 * Lay doi tuong AdminAuth
	 *
	 * @return AdminAuth
	 */
	public static function auth()
	{
		return static::makeService('AdminAuth');
	}

}