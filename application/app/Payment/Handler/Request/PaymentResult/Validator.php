<?php namespace App\Payment\Handler\Request\PaymentResult;

use App\Payment\PaymentFactory as PaymentFactory;
use App\Payment\Library\Payment\PaymentResultResponse;
use App\Payment\Validator\PaymentResult\Validator as PaymentResultValidator;
use App\Payment\Validator\PaymentResult\PaymentResultException;
use App\Payment\Validator\PaymentResult\Error;

class Validator
{
	/**
	 * Doi tuong request
	 *
	 * @var Request
	 */
	protected $request;

	/**
	 * Doi tuong PaymentResultValidator
	 *
	 * @var PaymentResultValidator
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

		t('lang')->load('modules/payment/common');
	}

	/**
	 * Thuc hien validate
	 *
	 * @throws PaymentResultException
	 */
	public function validate()
	{
		if ( ! $this->request->getTran())
		{
			$this->throwException(Error::TRAN_NOT_EXIST);
		}

		if ( ! $this->checkToken())
		{
			throw new PaymentResultException(
				Error::INPUT_INVALID,
				lang('notice_value_invalid', 'Token')
			);
		}

		if ( ! $this->checkPayment())
		{
			throw new PaymentResultException(
				Error::INPUT_INVALID,
				lang('notice_value_invalid', lang('payment'))
			);
		}

		$this->validator = new PaymentResultValidator(
			$this->request->getTran(),
			$this->request->get('page')
		);

		$this->validator->validate();
	}

	/**
	 * Kiem tra token
	 *
	 * @return bool
	 */
	protected function checkToken()
	{
		$tran = $this->request->getTran();

		$token = PaymentFactory::service()->makePaymentToken($tran);

		return $token === $this->request->get('token');
	}

	/**
	 * Kiem tra payment
	 *
	 * @return bool
	 */
	protected function checkPayment()
	{
		$tran = $this->request->getTran();

		$payment_key = $this->request->get('payment_key');

		return ($payment_key && $payment_key != $tran->payment_key) ? false : true;
	}

	/**
	 * Throw exception
	 *
	 * @param string $error
	 * @param array  $replace
	 * @throws PaymentResultException
	 */
	protected function throwException($error, $replace = [])
	{
		$message = lang('error_'.$error, $replace);

		throw new PaymentResultException($error, $message);
	}

	/**
	 * Lay ket qua tra ve tu payment
	 *
	 * @return PaymentResultResponse|null
	 */
	public function getPaymentResult()
	{
		return $this->validator ? $this->validator->getPaymentResult() : null;
	}
}