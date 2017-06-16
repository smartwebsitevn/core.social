<?php namespace App\Payment\Handler\Request\PaymentPay;

use App\Payment\Validator\PaymentPay\PaymentPayException;
use App\Payment\Job\PaymentPay as PaymentPayJob;

class Factory
{
	/**
	 * Doi tuong request
	 *
	 * @var Request
	 */
	protected $request;

	/**
	 * Doi tuong Validator
	 *
	 * @var Validator
	 */
	protected $validator;


	/**
	 * Khoi tao doi tuong
	 *
	 * @param Request $request
	 */
	public function __construct(Request $request)
	{
		$this->request = $request;

		$this->validator = new Validator($request);

		t('lang')->load('modules/payment/payment');
	}

	/**
	 * Thuc hien xu ly
	 */
	public function handle()
	{
		try
		{
			$this->validator->validate();
           
			return $this->success();
		}
		catch (PaymentPayException $e)
		{
			return $this->fail($e);
		}
	}

	/**
	 * Xu ly pay success
	 */
	protected function success()
	{
		$handler = new PaymentPayJob(
			$this->request->getTran(),
			$this->request->getPayment(),
			$this->request->getUser()
		);

		$handler->handle()->send();
	}

	/**
	 * Xu ly pay fail
	 *
	 * @param PaymentPayException $e
	 */
	protected function fail(PaymentPayException $e)
	{
		set_message($e->getMessage());

		redirect();
	}
}