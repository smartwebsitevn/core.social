<?php namespace App\Payment\PayGate\Purse;

use App\Payment\Library\PayGateService;
use App\Payment\Library\Payment\PaymentPayRequest;
use App\Payment\Library\Payment\PaymentPayResponse;
use App\Payment\Library\Payment\PaymentResultOutputRequest;
use App\Payment\Library\Payment\PaymentResultOutputResponse;
use App\Payment\Library\Payment\PaymentResultRequest;
use App\Payment\Library\Payment\PaymentResultResponse;
use App\Payment\Library\PaymentStatus;
use App\User\UserFactory as UserFactory;
use App\Purse\PurseFactory as PurseFactory;
use App\Purse\Handler\Request\PursePayment as PursePaymentRequestHandler;

class Service extends PayGateService
{
	/**
	 * Payment result response
	 *
	 * @var array
	 */
	protected $payment_result_response = [];

	/**
	 * Payment co su dung so du de thanh toan hay khong
	 *
	 * @return bool
	 */
	public function useBalance()
	{
		return true;
	}



	/**
	 * Thuc hien thanh toan
	 *
	 * @param PaymentPayRequest $request
	 * @return PaymentPayResponse
	 */
	public function paymentPay(PaymentPayRequest $request)
	{
		if ( ! UserFactory::auth()->logged())
		{
			return PaymentPayResponse::redirect('');
		}

		t('lang')->load('modules/purse/purse');

		$paygate = $this->factory;

		$payment = $this->model;

		$amount = $request->amount;

		$tran = $request->tran;

		$user = UserFactory::auth()->user();

		$purses = PurseFactory::purse()->userPurses($user)
			->whereLoose('currency_id', $tran->currency_id);

		$balance = $purses->sortByDesc('balance')->first()->balance;

		if ($balance >= $amount)
		{
			mod('user_security')->send('payment');
		}

		$data = compact('paygate', 'payment', 'purses', 'amount', 'tran');

		$data = array_merge($data, [
			'action'  => $request->url_result,
			'captcha' => site_url('captcha/four'),
			'format_amount' => currency_format_amount($amount, $payment->currency_id),
		]);

		return PaymentPayResponse::tpl($paygate->viewPath('payment_pay'), $data);
	}

	/**
	 * Xu ly ket qua thanh toan tra ve
	 *
	 * @param PaymentResultRequest $request
	 * @return PaymentResultResponse
	 */
	public function paymentResult(PaymentResultRequest $request)
	{
		$amount = $request->amount;

		$tran = $request->tran;

		$purse_payment = new PursePaymentRequestHandler($amount, $tran);

		// Validate
		if ( ! $purse_payment->validate())
		{
			$this->payment_result_response = $purse_payment->errors();

			return new PaymentResultResponse([
				'status' => PaymentStatus::NONE,
			]);
		}

		// Success
		$purse_payment->success();

		$this->payment_result_response = [
			'complete' => true,
			'location' => $tran->invoice->url('view'),
		];

		return new PaymentResultResponse([
			'status'  => PaymentStatus::SUCCESS,
			'amount'  => $amount,
			'tran_id' => $tran->id,
		]);
	}

	/**
	 * Tao payment result output
	 *
	 * @param PaymentResultOutputRequest $request
	 * @return PaymentResultOutputResponse|null
	 */
	public function paymentResultOutput(PaymentResultOutputRequest $request)
	{
		$response = json_encode($this->payment_result_response);

		return PaymentResultOutputResponse::content($response);
	}

}