<?php namespace App\LogActivity;

use App\LogActivity\Library\ActivityLogger;
use App\LogActivity\Library\ActivityLoggerManager;

class LogActivityFactory extends \Core\Base\Factory
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
	 * Lay doi tuong LoggerManager
	 *
	 * @return ActivityLoggerManager
	 */
	public static function loggerManager()
	{
		return static::makeServiceProvider('Library/ActivityLoggerManager');
	}

	/**
	 * Lay doi tuong logger
	 *
	 * @param string $key
	 * @return ActivityLogger
	 */
	public static function logger($key)
	{
		return static::loggerManager()->make($key);
	}
}