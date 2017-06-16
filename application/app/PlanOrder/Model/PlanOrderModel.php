<?php namespace App\PlanOrder\Model;

use App\Admin\Model\AdminRelationTrait;
use App\Currency\Model\CurrencyRelationTrait;
use App\Invoice\Library\OrderStatus;
use App\Invoice\Model\InvoiceOrderRelationTrait;
use App\Invoice\Model\InvoiceRelationTrait;
use App\Payment\Model\PaymentRelationTrait;
use App\Purse\Model\PurseRelationTrait;
use App\User\Model\UserRelationTrait;

class PlanOrderModel extends \Core\Base\Model
{
	use InvoiceRelationTrait;
	use InvoiceOrderRelationTrait;
	use PurseRelationTrait;
	use PaymentRelationTrait;
	use AdminRelationTrait;
	use UserRelationTrait;
	use CurrencyRelationTrait;

	protected $table = 'plan_order';

	protected $casts = [
		'amount'         => 'float',
		'options'        => 'array',
	];

	protected $defaults = [
		'options'  => [],
	];

	protected $formats = [
		'amount'         => 'amount',
		'created'        => 'date',
	];



	/**
	 * Kiem tra co the thuc hien hanh dong hay khong
	 *
	 * @param string $action
	 * @return bool
	 */
	public function can($action)
	{
		$status = $this->getAttribute('status');

		switch ($action)
		{
			case 'complete':
			case 'cancel':
			{
				return in_array($status, [OrderStatus::PENDING, OrderStatus::PROCESSING]);
			}
		}

		return parent::can($action);
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
		switch($key)
		{
			case 'amount':
			{
				$amount = $this->getAttribute($key);
				$currency_id = $this->getAttribute('currency_id');

				return currency_format_amount($amount, $currency_id);
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