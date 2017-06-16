<?php namespace App\Invoice\Library;

use Core\App\App;
use Core\Support\DriverManager;

class InvoiceServiceManager extends DriverManager
{
	/**
	 * Lay danh sach drivers
	 *
	 * @return array
	 */
	protected function getListDrivers()
	{
		$path = APPPATH.'app/Invoice/InvoiceService';

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
		return 'App\Invoice\InvoiceService\\'.$key;
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
			$service = $this->make($key);

			$result[] = array_merge($service->info(), [
				'key'  => $key,
				'type' => $service->type(),
			]);
		}

		return $result;
	}

}