<?php namespace App\Product\Model;

use TF\Support\Collection;

class OrderCardsModel extends \Core\Base\Model
{
	protected $table = 'product_order_cards';


	/**
	 * Gan card code
	 *
	 * @param string $value
	 */
	protected function setCodeAttribute($value)
	{
		$this->attributes['code'] = security_encrypt($value, 'encode');

		$this->attributes['code_encode'] = md5($value);
	}

	/**
	 * Lay card code
	 *
	 * @param string $value
	 * @return string
	 */
	protected function getCodeAttribute($value)
	{
		return security_encrypt($value, 'decode');
	}

	/**
	 * Lay danh sach cards cua order
	 *
	 * @param int $product_order_id
	 * @return Collection
	 */
	public static function listOfOrder($product_order_id)
	{
		$list = (new static)->newQuery()->get_list([
			'where' => compact('product_order_id'),
			'order' => ['id', 'asc'],
		]);

		return static::makeCollection($list);
	}

	/**
	 * Lay card tu code va serial
	 *
	 * @param string $code
	 * @param string $serial
	 * @return null|static
	 */
	public static function findCard($code, $serial)
	{
		$code_encode = md5($code);

		return static::findWhere(compact('code_encode', 'serial'));
	}

	/**
	 * Lay card tu code
	 *
	 * @param string $code
	 * @return null|static
	 */
	public static function findCardByCode($code)
	{
		$code_encode = md5($code);

		return static::findWhere(compact('code_encode'));
	}

}