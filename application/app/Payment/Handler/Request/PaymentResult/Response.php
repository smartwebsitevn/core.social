<?php namespace App\Payment\Handler\Request\PaymentResult;

use App\Payment\PaymentFactory as PaymentFactory;
use App\Payment\Library\Payment\PaymentResultOutputRequest;
use App\Payment\Library\Payment\PaymentResultOutputResponse;
use App\Payment\Model\PaymentModel as PaymentModel;

class Response
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
	 * Response constructor.
	 *
	 * @param Request   $request
	 * @param Validator $validator
	 */
	public function __construct(Request $request, Validator $validator)
	{
		$this->request = $request;
		$this->validator = $validator;
	}

	/**
	 * Tao response
	 *
	 * @param array $options
	 * @return PaymentResultOutputResponse
	 */
	public function make(array $options)
	{
		$response = null;

		if ($payment = $this->request->getPayment())
		{
			$response = $this->makePaymentResultOutput($payment, $options);
		}

		return $response ?: $this->makeResponseDefault();
	}

	/**
	 * Tao PaymentResultOutputResponse
	 *
	 * @param PaymentModel $payment
	 * @param array        $options
	 * @return PaymentResultOutputResponse|null
	 */
	protected function makePaymentResultOutput(PaymentModel $payment, array $options)
	{
		$options = array_merge($options, [
			'page' => $this->request->get('page'),
			'tran' => $this->request->getTran(),
			'payment_result' => $this->validator->getPaymentResult(),
		]);

		$request = new PaymentResultOutputRequest($options);

		return PaymentFactory::makePaygateService($payment->key)->paymentResultOutput($request);
	}

	/**
	 * Tao response mac dinh
	 *
	 * @return PaymentResultOutputResponse
	 */
	protected function makeResponseDefault()
	{
		$url = ($tran = $this->request->getTran()) ? $tran->invoice->url('view') : site_url();

		return PaymentResultOutputResponse::redirect($url);
	}

}