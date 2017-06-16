<?php namespace App\Invoice\Library;

class InvoiceStatus
{
	const UNPAID = 'unpaid';
	const PAID = 'paid';
	const CANCELED = 'canceled';


	/**
	 * Lay danh sach status
	 *
	 * @return array
	 */
	public static function lists()
	{
		return [
			static::UNPAID,
			static::PAID,
			static::CANCELED,
		];
	}

	/**
	 * Kiem tra status co ton tai hay khong
	 *
	 * @param string $status
	 * @return bool
	 */
	public static function has($status)
	{
		return in_array($status, static::lists());
	}
}