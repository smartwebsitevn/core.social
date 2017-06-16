<?php namespace App\Invoice\Library;

class OrderStatus
{
	const PENDING = 'pending';
	const PROCESSING = 'processing';
	const COMPLETED = 'completed';
	const CANCELED = 'canceled';
	const FAILED = 'failed';
	const EXPIRED = 'expired';
	const REFUNDED = 'refunded';
	const CHARGEBACK = 'chargeback';


	/**
	 * Lay danh sach status
	 *
	 * @return array
	 */
	public static function lists()
	{
		return [
			static::PENDING,
			static::PROCESSING,
			static::COMPLETED,
			static::CANCELED,
			static::FAILED,
			static::EXPIRED,
			static::REFUNDED,
			static::CHARGEBACK,
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