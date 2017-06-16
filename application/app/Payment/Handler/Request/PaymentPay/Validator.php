<?php namespace App\Payment\Handler\Request\PaymentPay;

use App\Payment\PaymentFactory as PaymentFactory;
use App\Payment\Validator\PaymentPay\Error;
use App\Payment\Validator\PaymentPay\Validator as PaymentPayValidator;
use App\Payment\Validator\PaymentPay\PaymentPayException;

class Validator
{
	/**
	 * Doi tuong request
	 *
	 * @var Request
	 */
	protected $request;


	/**
	 * Khoi tao doi tuong
	 *
	 * @param Request $request
	 */
	public function __construct(Request $request)
	{
		$this->request = $request;

		t('lang')->load('modules/payment/common');
	}

	/**
	 * Thuc hien validate
	 *
	 * @throws PaymentPayException
	 */
	public function validate()
	{
		if ( ! $this->checkToken())
		{
			throw new PaymentPayException(
				Error::INPUT_INVALID,
				lang('notice_value_invalid', 'Token')
			);
		}

		if ( ! $this->request->getTran())
		{
			$this->throwException(Error::TRAN_NOT_EXIST);
		}

		if ( ! $this->request->getPayment())
		{
		    $this->throwException(Error::PAYMENT_NOT_EXIST);
		}

		if ( ! $this->checkExpire())
		{
			throw new PaymentPayException(
				Error::INPUT_INVALID,
				lang('error_payment_session_expired', 'Token')
			);
		}

		$validator = new PaymentPayValidator(
			$this->request->getTran(),
			$this->request->getPayment(),
			$this->request->getUser()
		);

		$validator->validate();
	}

	/**
	 * Kiem tra token
	 *
	 * @return bool
	 */
	protected function checkToken()
	{
		$token = PaymentFactory::service()->makePaymentPayToken($this->request->get());

		return $token === $this->request->get('token');
	}

	/**
	 * Kiem tra expire
	 *
	 * @return bool
	 */
	protected function checkExpire()
	{
		return $this->request->get('expire') >= now();
	}

	/**
	 * Throw exception
	 *
	 * @param string $error
	 * @param array  $replace
	 * @throws PaymentPayException
	 */
	protected function throwException($error, $replace = [])
	{
		$message = lang('error_'.$error, $replace);

		throw new PaymentPayException($error, $message);
	}

}