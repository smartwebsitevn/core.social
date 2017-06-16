<?php namespace App\Payment\Handler\Request\PaymentResult;

use App\Payment\Library\Payment\PaymentResultOutputResponse;
use App\Payment\Library\Payment\PaymentResultResponse;
use App\Payment\Library\PaymentStatus;
use App\Payment\Validator\PaymentResult\PaymentResultException;
use App\Transaction\TranFactory as TranFactory;
use App\Transaction\Model\TranModel as TranModel;

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
	 * Doi tuong response
	 *
	 * @var Response
	 */
	protected $response;


	/**
	 * Khoi tao doi tuong
	 *
	 * @param Request $request
	 */
	public function __construct(Request $request)
	{
		$this->request = $request;

		$this->validator = new Validator($request);

		$this->response = new Response($this->request, $this->validator);

		t('lang')->load('modules/payment/payment');
	}

	/**
	 * Thuc hien xu ly
	 */
	public function handle()
	{
		lib('csrf')->delay();

		$this->request->init();

		try
		{
			$this->validator->validate();

			$response = $this->success();
		}
		catch (PaymentResultException $e)
		{
			$response = $this->fail($e);
		}
		
		$response->send();
	}

	/**
	 * Xu ly payment success
	 *
	 * @return PaymentResultOutputResponse
	 */
	protected function success()
	{
		$tran = $this->request->getTran();

		$payment_result = $this->validator->getPaymentResult();

		$this->savePaymentResult($tran, $payment_result);

		if ($payment_result->status == PaymentStatus::SUCCESS)
		{
			$tran->update([
				'success_at' => now(),
				'paying'     => false,
			]);

			TranFactory::tran()->active($tran);

			set_message(lang('notice_payment_success'));
		}

		return $this->response->make([
			'status' => true,
		]);
	}

	/**
	 * Xu ly payment fail
	 *
	 * @param PaymentResultException $exception
	 * @return PaymentResultOutputResponse
	 */
	protected function fail(PaymentResultException $exception)
	{
		$tran = $this->request->getTran();

		$payment_result = $this->validator->getPaymentResult();

		if ($tran)
		{
		    $tran->update(['paying' => false]);
		}

		if ($tran && $payment_result)
		{
			$this->savePaymentResult($tran, $payment_result);

			TranFactory::tran()->fail($tran);
		}

		log_message('error', $exception->getMessage());

		set_message(lang('notice_payment_fail', [
			'error' => $exception->getMessage(),
		]));

		return $this->response->make([
			'status'  => false,
			'error'   => $exception->getError(),
			'message' => $exception->getMessage(),
		]);
	}

	/**
	 * Luu ket qua tra ve tu payment
	 *
	 * @param TranModel             $tran
	 * @param PaymentResultResponse $payment_result
	 */
	protected function savePaymentResult(TranModel $tran, PaymentResultResponse $payment_result)
	{
		if ($payment_tran_id = $payment_result->payment_tran_id)
		{
			$tran->update(compact('payment_tran_id'));
		}

		$info = array_filter([
			'payment_tran'  => $payment_result->payment_tran,
			'payment_error' => $payment_result->error,
		]);

		if (count($info))
		{
			$tran->updateTranInfo($info);
		}
	}

}