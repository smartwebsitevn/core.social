<?php namespace App\DepositCard\Model;

use App\Currency\Model\CurrencyRelationTrait;
use App\Invoice\Model\InvoiceOrderRelationTrait;
use App\Invoice\Model\InvoiceRelationTrait;
use App\Purse\Model\PurseRelationTrait;
use App\User\Model\UserRelationTrait;

class DepositCardModel extends \Core\Base\Model
{
	use InvoiceRelationTrait;
	use InvoiceOrderRelationTrait;
	use PurseRelationTrait;
	use UserRelationTrait;
	use CurrencyRelationTrait;
	use CardTypeRelationTrait;

	protected $table = 'deposit_card';

	protected $casts = [
		'profit'        => 'float',
		'profit_amount' => 'float',
		'card_amount'   => 'float',
		'amount'        => 'float',
		'discount'      => 'float',
		'data'          => 'array',
	];

	protected $defaults = [
		'data' => [],
	];

	protected $formats = [
		'profit'        => 'amount',
		'profit_amount' => 'amount',
		'card_amount'   => 'amount',
		'amount'        => 'amount',
		'discount'      => 'amount',
		'created'       => 'date',
	];


	/**
	 * Tao doi tuong moi va gan $attributes
	 *
	 * @param array $attributes
	 * @return static
	 */
	public static function newWithAttributes(array $attributes)
	{
		$relations = static::pullRelationsFromAttributes($attributes, [
			'invoice_order' => 'App\Invoice\Model\InvoiceOrderModel',
			'purse'         => 'App\Purse\Model\PurseModel',
			'card_type'     => 'App\Deposit\Model\CardTypeModel',
			'user'          => 'App\User\Model\UserModel',
		]);

		return parent::newWithAttributes($attributes)->fill($relations);
	}

	/**
	 * Format du lieu
	 *
	 * @param string $key
	 * @param mixed  $option
	 * @return string|null
	 */
	public function format($key, $option = null)
	{
		$currency_id = $this->getAttribute('currency_id');

		switch($key)
		{
			case 'profit_amount':
			case 'amount':
			{
				$value = $this->getAttribute($key);

				return currency_format_amount($value, $currency_id);
			}

			case 'card_amount':
			{
				$value = $this->getAttribute($key);

				return number_format($value);
			}
		}

		return parent::format($key, $option);
	}

	/**
	 * Cap nhat trang thai
	 *
	 * @param string $status
	 */
	public function updateStatus($status)
	{
		$this->update(compact('status'));

		$this->getAttribute('invoice_order')->update(['order_status' => $status]);
	}

	/**
	 * Lay thong tin tu $invoice_order_id
	 *
	 * @param int $invoice_order_id
	 * @return static|null
	 */
	public static function findByInvoiceOrder($invoice_order_id)
	{
		return static::findWhere(compact('invoice_order_id'));
	}

}