<?php namespace App\Product\Library;

class ProductType
{
	const CARD = 'card';
	const TOPUP_MOBILE = 'topup_mobile';
	const TOPUP_MOBILE_POST = 'topup_mobile_post';
	const TOPUP_GAME = 'topup_game';
	const SHIP = 'ship';

	/**
	 * Lay danh sach types
	 *
	 * @return array
	 */
	public static function lists()
	{
		return [
			static::CARD,
			static::TOPUP_MOBILE,
			static::TOPUP_MOBILE_POST,
			static::TOPUP_GAME,
			static::SHIP,
		];
	}

	/**
	 * Kiem tra type co ton tai hay khong
	 *
	 * @param string $type
	 * @return bool
	 */
	public static function has($type)
	{
		return in_array($type, static::lists());
	}
}