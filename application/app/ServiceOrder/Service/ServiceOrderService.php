<?php namespace App\ServiceOrder\Service;

use App\Currency\Model\CurrencyModel;
use App\Purse\Model\PurseModel as PurseModel;
use Core\Support\Number;

class ServiceOrderService
{
	/**
	 * Lay setting
	 *
	 * @param string $key
	 * @param mixed  $default
	 * @return mixed
	 */
	public function setting($key = null, $default = null)
	{
		$setting = module_get_setting('service_order');

		return array_get($setting, $key, $default);
	}

}