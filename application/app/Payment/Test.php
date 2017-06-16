<?php namespace App\Payment;

class Test
{
	public static function _t()
	{
		$payment = \App\Payment\Model\PaymentModel::find(2);

		$v = $payment->paygateServiceInstance()->withdraw();
		$v = $payment->paygateServiceInstance()->withdraw();
		$v = $payment->paygateServiceInstance()->withdraw();

		pr($v, 0);
	}

	public static function payment()
	{
		$tran = \App\Transaction\Model\TranModel::find(3);
		$payment = \App\Payment\Model\PaymentModel::find(2);

		//$tran->id = 66;
		//$payment->id = 22;

		$url = \App\Payment\PaymentFactory::service()->urlPaymentPay($tran, $payment);

		pr($url);
	}
}