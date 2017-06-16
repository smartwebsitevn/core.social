<?php namespace App\Payment\Handler\Request\PaymentPay;

use App\Transaction\Model\TranModel as TranModel;
use App\Payment\PaymentFactory as PaymentFactory;
use App\Payment\Model\PaymentModel as PaymentModel;
use App\User\UserFactory as UserFactory;
use App\User\Model\UserModel as UserModel;
use Core\Support\RequestAccess;

class Request extends RequestAccess
{
	/**
	 * Lay thong tin tran
	 *
	 * @return TranModel|null
	 */
	public function getTran()
	{
		if ( ! array_key_exists('tran', $this->data))
		{
			$this->data['tran'] = TranModel::find($this->get('tran_id'));
		}

		return $this->data['tran'];
	}

	/**
	 * Lay thong tin payment
	 *
	 * @return PaymentModel|null
	 */
	public function getPayment()
	{
		if ( ! array_key_exists('payment', $this->data))
		{
			$this->data['payment'] = PaymentFactory::paymentManager()->findById($this->get('payment_id'));
		}

		return $this->data['payment'];
	}

	/**
	 * Lay thong tin user
	 *
	 * @return UserModel
	 */
	public function getUser()
	{
		return UserFactory::auth()->user();
	}
}