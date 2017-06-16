<?php namespace App\Invoice;

class Service
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
		$setting = [
			'payment_due' => 3*24*60*60, // 3 day
		];

		return array_get($setting, $key, $default);
	}

	/**
	 * Lay ki han thanh toan mac dinh
	 *
	 * @return int
	 */
	public function getPaymentDueDefault()
	{
		return now() + $this->setting('payment_due');
	}

}