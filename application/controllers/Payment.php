<?php

use App\Payment\Handler\Request\PaymentPay\Factory as PaymentPay;
use App\Payment\Handler\Request\PaymentPay\Request as PaymentPayRequest;
use App\Payment\Handler\Request\PaymentResult\Factory as PaymentResult;
use App\Payment\Handler\Request\PaymentResult\Request as PaymentResultRequest;

class Payment extends MY_Controller
{
	/**
	 * Thanh toan giao dich
	 */
	public function pay()
	{
		$request = new PaymentPayRequest(t('input')->get());
		(new PaymentPay($request))->handle();
	}

	/**
	 * Nhan ket qua thanh toan
	 */
	public function result()
	{
	   
		$this->_dispatchPaymentResult('result');
	}

	/**
	 * Nhan thong bao ket qua thanh toan
	 */
	public function notify()
	{
		$this->_dispatchPaymentResult('notify');
	}

	/**
	 * Chay PaymentResult
	 *
	 * @param string $page
	 */
	protected function _dispatchPaymentResult($page)
	{
		$request = $this->_makePaymentResultRequest($page);
        
		(new PaymentResult($request))->handle();
	}

	/**
	 * Tao PaymentResultRequest
	 *
	 * @param string $page
	 * @return PaymentResultRequest
	 */
	protected function _makePaymentResultRequest($page)
	{
		$arr = explode('-', t('uri')->rsegment(3), 2);

		if (count($arr) == 1)
		{
			$input = [
				'payment_key' => $arr[0],
			];
		}
		else
		{
			$input = [
				'tran_id' => $arr[0],
				'token'   => $arr[1],
			];
		}

		$input['page'] = $page;

		return new PaymentResultRequest($input);
	}

}