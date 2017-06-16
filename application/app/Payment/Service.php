<?php namespace App\Payment;

use Core\Support\Arr;
use Core\Support\Traits\ErrorLangTrait;
use App\Transaction\Model\TranModel as TranModel;
use App\Payment\Model\PaymentModel as PaymentModel;

class Service
{
	use ErrorLangTrait;

	/**
	 * Lay duong dan file error lang
	 *
	 * @return string
	 */
	protected function getErrorLangPath()
	{
		return 'modules/payment/common';
	}

	/**
	 * Lay setting
	 *
	 * @param string $key
	 * @param mixed  $default
	 * @return mixed
	 */
	public function setting($key = null, $default = null)
	{
		$setting = [
			'url_payment_pay_expire' => 2*60*60, // 2h
		];

		return array_get($setting, $key, $default);
	}

	/**
	 * Tao payment token
	 *
	 * @param TranModel $tran
	 * @return string
	 */
	public function makePaymentToken(TranModel $tran)
	{
		return security_encode($tran->id.'-'.$tran->payment_id.'-'.$tran->pay_request_id);
	}

	/**
	 * Tao payment pay token
	 *
	 * @param array $data
	 * @return string
	 */
	public function makePaymentPayToken(array $data)
	{
		$input = Arr::pick($data, ['tran_id', 'payment_id', 'expire']);

		return security_create_code($input);
	}

	/**
	 * Tao url payment pay
	 *
	 * @param TranModel    $tran
	 * @param PaymentModel $payment
	 * @param $payment_params  cac tham so rieng cua cong thanh toan
	 * @return string
	 */
	public function urlPaymentPay(TranModel $tran, PaymentModel $payment,$payment_params=array())
	{
		$query = [
			'tran_id'    => $tran->id,
			'payment_id' => $payment->id,
			'expire'     => now() + $this->setting('url_payment_pay_expire'),
		];

		$query['token'] = $this->makePaymentPayToken($query);

		if($payment_params)
			$query = array_merge($query,$payment_params);
		return site_url('payment/pay').'?'.http_build_query($query);
	}

	/**
	 * Tao url payment result
	 *
	 * @param TranModel $tran
	 * @param string    $page
	 * @return string
	 */
	public function urlPaymentResult(TranModel $tran, $page = 'result')
	{
		$token = $this->makePaymentToken($tran);

		return site_url("payment/{$page}/{$tran->id}-{$token}");
	}

}