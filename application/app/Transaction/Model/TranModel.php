<?php namespace App\Transaction\Model;

use App\Invoice\Model\InvoiceModel as InvoiceModel;
use App\Payment\PaymentFactory as PaymentFactory;
use App\Payment\Model\PaymentModel as PaymentModel;
use App\Transaction\Library\TranStatus;
use App\Transaction\Model\TranInfoModel as TranInfoModel;
use App\User\Model\UserModel as UserModel;
use App\Currency\CurrencyFactory as CurrencyFactory;
use App\Currency\Model\CurrencyModel as CurrencyModel;
use Core\Model\TokenMakerTrait;

class TranModel extends \Core\Base\Model
{
	use TokenMakerTrait;

	protected $table = 'tran';

	protected $microtime = true;

	protected $casts = [
		'amount'         => 'float',
		'payment_amount' => 'float',
		'payment_fee'    => 'float',
		'payment_net'    => 'float',
		'currency_value' => 'float',
		'paying'         => 'bool',
	];

	protected $formats = [
		'amount'     => 'amount',
		'created'    => 'date',
		'pay_at'     => 'date',
		'success_at' => 'date',
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
			'invoice' => 'App\Invoice\Model\InvoiceModel',
			'user'    => 'App\User\Model\UserModel',
		]);

		return parent::newWithAttributes($attributes)->fill($relations);
	}

	/**
	 * Gan thong tin invoice
	 *
	 * @param Invoice $invoice
	 */
	protected function setInvoiceAttribute(InvoiceModel $invoice)
	{
		$this->additional['invoice'] = $invoice;
	}

	/**
	 * Lay thong tin invoice
	 *
	 * @return InvoiceModel|null
	 */
	protected function getInvoiceAttribute()
	{
		if ( ! array_key_exists('invoice', $this->additional))
		{
			$invoice_id = $this->getAttribute('invoice_id');

			$this->additional['invoice'] = InvoiceModel::find($invoice_id);
		}

		return $this->additional['invoice'];
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
	 * Lay thong tin user
	 *
	 * @return UserModel|null
	 */
	protected function getUserAttribute()
	{
		if ( ! array_key_exists('user', $this->additional))
		{
			$user_id = $this->getAttribute('user_id');

			$this->additional['user'] = UserModel::find($user_id);
		}

		return $this->additional['user'];
	}

	/**
	 * Lay thong tin payment
	 *
	 * @return PaymentModel|null
	 */
	protected function getPaymentAttribute()
	{
		if ( ! array_key_exists('payment', $this->additional))
		{
			$payment_id = $this->getAttribute('payment_id');

			$this->additional['payment'] = PaymentFactory::paymentManager()->findById($payment_id);
		}

		return $this->additional['payment'];
	}

	/**
	 * Lay currency
	 *
	 * @return CurrencyModel|null
	 */
	protected function getCurrencyAttribute()
	{
		$currency_id = $this->getAttribute('currency_id');

		return CurrencyFactory::currency()->find($currency_id);
	}

	/**
	 * Lay tran_info
	 *
	 * @return TranInfoModel|null
	 */
	protected function getTranInfoAttribute()
	{
		if ( ! array_key_exists('tran_info', $this->additional))
		{
			$this->additional['tran_info'] = TranInfoModel::find($this->getKey()) ?: new TranInfoModel();
		}

		return $this->additional['tran_info'];
	}

	/**
	 * lay user_country_code
	 *
	 * @return string
	 */
	protected function getUserCountryCodeAttribute()
	{
		$ip = (string) $this->getAttribute('user_ip');

		return lib('geoip')->country_code($ip) ?: 'VN';
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
			case 'cancel':
			{
				return $status == TranStatus::PENDING;
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
	 * Update tran info
	 *
	 * @param array $attributes
	 * @return TranInfoModel
	 */
	public function updateTranInfo(array $attributes)
	{
		return TranInfoModel::updateOrCreate(['tran_id' => $this->getKey()], $attributes);
	}

}