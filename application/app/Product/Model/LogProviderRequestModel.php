<?php namespace App\Product\Model;

use Core\Support\Arr;
use TF\Support\Collection;
use App\Product\ProductFactory as ProductFactor;

class LogProviderRequestModel extends \Core\Base\Model
{
	protected $table = 'log_product_provider_request';

	protected $casts = [
		'input'         => 'array',
		'output'        => 'array',
		'status'        => 'bool',
		'provider_tran' => 'array',
		'balance'       => 'float',
	];

	protected $defaults = [
		'input'         => [],
		'output'        => [],
		'provider_tran' => [],
	];

	protected $formats = [
		'created'   => 'date',
		'completed' => 'date',
	];


	/**
	 * Gan output attribute
	 *
	 * @param array $value
	 */
	protected function setOutputAttribute(array $value)
	{
		if (isset($value['cards']))
		{
			$value['cards'] = $this->encodeCards($value['cards']);
		}

		$this->attributes['output'] = json_encode($value);
	}

	/**
	 * Lay output attribute
	 *
	 * @param string $value
	 * @return array
	 */
	protected function getOutputAttribute($value)
	{
		$value = json_decode($value, true);

		$value = is_array($value) ? $value : [];

		if (isset($value['cards']))
		{
			$value['cards'] = $this->decodeCards($value['cards']);
		}

		return $value;
	}

	/**
	 * Encode cards
	 *
	 * @param array $cards
	 * @return array
	 */
	protected function encodeCards(array $cards)
	{
		foreach ($cards as &$card)
		{
			$card = Arr::toArray($card);

			$code = $card['code'];

			$card['code'] = security_encrypt($code, 'encode');

			$card['code_encode'] = md5($code);
		}

		return $cards;
	}

	/**
	 * Decode cards
	 *
	 * @param array $cards
	 * @return array
	 */
	protected function decodeCards(array $cards)
	{
		foreach ($cards as &$card)
		{
			$card['code'] = security_encrypt($card['code'], 'decode');
		}

		return $cards;
	}

	/**
	 * Get provider attribute
	 *
	 * @return mixed
	 */
	protected function getProviderAttribute()
	{
		$provider_key = $this->getAttribute('provider_key');

		return ProductFactor::providerManager()->data($provider_key);
	}

	/**
	 * Lay danh sach request cua invoice_order_id
	 *
	 * @param int $invoice_order_id
	 * @return Collection
	 */
	public static function listOfInvoiceOrder($invoice_order_id)
	{
		$list = (new static)->newQuery()->get_list([
			'where' => compact('invoice_order_id'),
		]);

		return static::makeCollection($list);
	}

}