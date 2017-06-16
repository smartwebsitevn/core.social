<?php namespace App\Withdraw\Handler\Form\WithdrawPayment;

use Core\Support\AttributesAccess;
use App\Purse\PurseFactory as PurseFactory;
use App\Purse\Model\PurseModel as PurseModel;
use App\Payment\PaymentFactory as PaymentFactory;
use App\Payment\Model\PaymentModel as PaymentModel;
use App\Withdraw\WithdrawFactory as WithdrawFactory;

class WithdrawPaymentForm extends AttributesAccess
{
	/**
	 * Danh sach attribute bo sung
	 *
	 * @var array
	 */
	protected $additional = [];

	/**
	 * Key luu tru
	 */
	const STORAGE_KEY = 'withdraw_payment';


	/**
	 * Lay thong tin purse
	 *
	 * @return PurseModel|null
	 */
	protected function getPurseAttribute()
	{
		if ( ! array_key_exists('purse', $this->additional))
		{
			$purse_number = $this->getAttribute('purse_number');

			$this->additional['purse'] = PurseFactory::purse()->findByNumber($purse_number);
		}

		return $this->additional['purse'];
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
	 * Lay fee
	 *
	 * @return float
	 */
	protected function getFeeAttribute()
	{
		return $this->getAmount('fee');
	}

	/**
	 * Lay receive_amount
	 *
	 * @return float
	 */
	protected function getReceiveAmountAttribute()
	{
		return $this->getAmount('receive_amount');
	}

	/**
	 * Lay withdraw amount
	 *
	 * @param string $key
	 * @return float
	 */
	protected function getAmount($key = null)
	{
		if ( ! array_key_exists('amounts', $this->additional))
		{
			$this->additional['amounts'] = $this->makeAmounts();
		}

		return array_get($this->additional['amounts'], $key);
	}

	/**
	 * Tinh withdraw amounts
	 *
	 * @return array
	 */
	protected function makeAmounts()
	{
		$purse = $this->getAttribute('purse');

		$amount = $this->getAttribute('amount');

		$payment = $this->getAttribute('payment');
		
		$receiver = $this->getAttribute('receiver');
		
		return ($purse && $payment)
			? WithdrawFactory::withdraw()->getAmounts($purse, $amount, $payment, $receiver)
			: [];
	}

	/**
	 * Lay receiver_info
	 *
	 * @return array
	 */
	protected function getReceiverInfoAttribute()
	{
		$payment = $this->getAttribute('payment');

		$withdraw = $payment->paygateServiceInstance()->withdraw();

		$receiver = $this->getAttribute('receiver');

		return $withdraw->formConfig("receiver[{$payment->id}]", $receiver);
	}

	/**
	 * Format du lieu
	 *
	 * @param $key
	 * @return string
	 */
	public function format($key)
	{
		switch($key)
		{
			case 'amount':
			case 'fee':
			{
				$amount = $this->getAttribute($key);

				$currency_id = $this->getAttribute('purse')->currency_id;

				return currency_format_amount($amount, $currency_id);
			}

			case 'receive_amount':
			{
				$amount = $this->getAttribute($key);

				$currency_id = $this->getAttribute('payment')->currency_id;

				return currency_format_amount($amount, $currency_id);
			}
		}
	}

	/**
	 * Luu du lieu
	 */
	public function save()
	{
		t('session')->set_userdata(static::STORAGE_KEY, $this->attributes);
	}

	/**
	 * Lay thong tin
	 *
	 * @return null|static
	 */
	public static function get()
	{
		$attributes = t('session')->userdata(static::STORAGE_KEY);

		return $attributes ? new static($attributes) : null;
	}

	/**
	 * Xoa du lieu
	 */
	public static function delete()
	{
		t('session')->unset_userdata(static::STORAGE_KEY);
	}

}