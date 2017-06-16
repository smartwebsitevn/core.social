<?php namespace App\Currency;

class Service
{
	/**
	 * Khoi tao doi tuong
	 */
	public function __construct()
	{
		t('config')->load('mod/currency', true, true);
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
		return array_get(config('mod/currency', ''), $key, $default);
	}

}