<?php namespace App\LogActivity\Library;

use Core\Support\DriverManager;

class ActivityLoggerManager extends DriverManager
{
	/**
	 * Lay danh sach drivers
	 *
	 * @return array
	 */
	protected function getListDrivers()
	{
		$path = APPPATH.'app/LogActivity/ActivityLogger';

		$files = App::file()->files($path);

		$list = [];

		foreach ($files as $file)
		{
			$list[] = basename($file, '.php');
		}

		return $list;
	}

	/**
	 * Lay class cua driver
	 *
	 * @param string $key
	 * @return string
	 */
	protected function getDriverClass($key)
	{
		return 'App\LogActivity\ActivityLogger\\'.$key;
	}

	/**
	 * Lay danh sach (bao gom ca thong tin)
	 *
	 * @return array
	 */
	public function listInfo()
	{
		$result = [];

		foreach ($this->lists() as $key)
		{
			$result[] = array_merge($this->make($key)->info(), compact('key'));
		}

		return $result;
	}

}