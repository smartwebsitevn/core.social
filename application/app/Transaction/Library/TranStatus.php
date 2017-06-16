<?php namespace App\Transaction\Library;

class TranStatus
{
	const PENDING = 'pending';
	const SUCCESS = 'success';
	const FAILED = 'failed';
	const CANCELED = 'canceled';
	const FRAUDE = 'fraude';

	/**
	 * Lay danh sach status
	 *
	 * @return array
	 */
	public static function lists()
	{
		return [
			static::PENDING,
			static::SUCCESS,
			static::FAILED,
			static::CANCELED,
			static::FRAUDE,
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