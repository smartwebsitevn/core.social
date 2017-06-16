<?php namespace App\Payment\Model;

use App\Payment\PaymentFactory as PaymentFactory;
use App\Payment\Model\PaymentModel as PaymentModel;

trait PaymentRelationTrait
{
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

}