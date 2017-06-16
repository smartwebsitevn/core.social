<?php namespace App\StockCard;

class Service
{
	/**
	 * Khoi tao doi tuong
	 */
	public function __construct()
	{
		t('config')->load('mod/stock_card', true, true);
	}

	/**
	 * Lay config
	 *
	 * @param string $key
	 * @param mixed  $default
	 * @return mixed
	 */
	public function config($key = null, $default = null)
	{
		return array_get(config('mod/stock_card', ''), $key, $default);
	}

}