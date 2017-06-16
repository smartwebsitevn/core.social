<?php namespace App\Invoice\Model;

use App\Invoice\Library\InvoiceStatus;
use App\Invoice\Model\InvoiceOrderModel as InvoiceOrderModel;
use App\Transaction\Library\TranStatus;
use App\Transaction\Model\TranModel as TranModel;
use App\User\Model\UserModel as UserModel;
use App\User\Model\UserRelationTrait;
use Core\Model\TokenMakerTrait;
use TF\Support\Collection;

class InvoiceModel extends \Core\Base\Model
{
	use UserRelationTrait;
	use TokenMakerTrait;

	protected $table = 'invoice';

	protected $microtime = true;

	protected $casts = [
		'info_contact'     => 'array',
		'info_shipping'    => 'array',
		'info_pay_to'      => 'array',
		'info_system'      => 'array',
		'params'           => 'array',
		'amounts_currency' => 'array',
		'fee_shipping'     => 'float',
		'fee_tax'          => 'float',
		'amount'           => 'float',
	];

	protected $defaults = [
		'info_contact'     => [],
		'info_shipping'    => [],
		'info_pay_to'      => [],
		'info_system'      => [],
		'params'           => [],
		'amounts_currency' => [],
	];

	protected $formats = [
		'fee_shipping' => 'amount',
		'fee_tax'      => 'amount',
		'amount'       => 'amount',
		'payment_due'  => 'date',
		'created'      => 'date',
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

			'user' => 'App\User\Model\UserModel',

			'trans' => [
				'type'  => 'many',
				'class' => 'App\Transaction\Model\TranModel',
			],

			'invoice_orders' => [
				'type'  => 'many',
				'class' => 'App\Invoice\Model\InvoiceOrderModel',
			],

		]);

		return parent::newWithAttributes($attributes)->fill($relations);
	}

	/**
	 * Gan thong tin user
	 *
	 * @param UserModel|null $user
	 */
	protected function setUserAttribute($user)
	{
		$this->additional['user'] = $user;
	}

	/**
	 * Gan danh sach trans cua invoice
	 *
	 * @param Collection $trans
	 */
	protected function setTransAttribute(Collection $trans)
	{
		$this->additional['trans'] = $trans;
	}

	/**
	 * Lay danh sach trans cua invoice
	 *
	 * @return Collection
	 */
	protected function getTransAttribute()
	{
		if ( ! array_key_exists('trans', $this->additional))
		{
			$invoice_id = $this->getKey();

			$list = (new TranModel())->newQuery()->get_list([
				'where' => compact('invoice_id'),
			]);

			$list = TranModel::makeCollection($list)->sortBy('id');

			$this->additional['trans'] = $list;
		}

		return $this->additional['trans'];
	}

	/**
	 * Lay giao dich thanh cong cua invoice (tran)
	 *
	 * @return TranModel|null
	 */
	protected function getTranAttribute()
	{
		return $this->getAttribute('trans')->whereLoose('status', TranStatus::SUCCESS)->first();
	}

	/**
	 * Lay trang thai giao dich cua invoice (tran_status)
	 *
	 * @return string
	 */
	protected function getTranStatusAttribute()
	{
		if ($tran = $this->getAttribute('tran'))
		{
		    return $tran->status;
		}

		return ($this->getAttribute('status') == InvoiceStatus::PAID)
			? TranStatus::SUCCESS : TranStatus::PENDING;
	}

	/**
	 * Gan danh sach invoice_orders cua invoice
	 *
	 * @param Collection $invoice_orders
	 */
	protected function setInvoiceOrdersAttribute(Collection $invoice_orders)
	{
		$this->additional['invoice_orders'] = $invoice_orders;
	}

	/**
	 * Lay danh sach invoice_orders cua invoice
	 *
	 * @return Collection
	 */
	protected function getInvoiceOrdersAttribute()
	{
		if ( ! array_key_exists('invoice_orders', $this->additional))
		{
			$invoice_id = $this->getKey();

			$list = (new InvoiceOrderModel)->newQuery()->get_list([
				'where' => compact('invoice_id'),
			]);

			$list = InvoiceOrderModel::makeCollection($list)->sortBy('id');

			$this->additional['invoice_orders'] = $list;
		}

		return $this->additional['invoice_orders'];
	}

	/**
	 * Lay customer_name
	 *
	 * @return string
	 */
	protected function getCustomerNameAttribute()
	{
		$name = array_get($this->getAttribute('info_contact'), 'name');

		if ( ! $name && ! $this->getAttribute('user_id'))
		{
		    $name = lang('guest');
		}

		return $name;
	}

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
			case 'pay':
			case 'active':
			{
				return (
					$status == InvoiceStatus::UNPAID
					&& $this->getAttribute('payment_due') > now()
				);
			}
		}

		return parent::can($action);
	}

	/**
	 * Tao url
	 *
	 * @param string $action
	 * @param array  $opt
	 * @return string
	 */
	public function url($action, array $opt = [])
	{
		switch ($action)
		{
			case 'payment':
			case 'view':
			{
				return parent::url($action, $opt).'?'.http_build_query([
					'token' => $this->token($action),
				]);
			}
		}

		return parent::url($action, $opt);
	}

	/**
	 * Cap nhat trang thai
	 *
	 * @param string $status
	 */
	public function updateStatus($status)
	{
		$this->update(compact('status'));

		$invoice_orders = $this->getAttribute('invoice_orders');

		foreach ($invoice_orders as $invoice_order)
		{
			$invoice_order->update(['invoice_status' => $status]);
		}
	}

}