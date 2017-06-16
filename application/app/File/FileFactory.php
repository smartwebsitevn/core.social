<?php namespace App\File;

class FileFactory extends \Core\Base\Factory
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

}