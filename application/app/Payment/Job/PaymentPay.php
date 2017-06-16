<?php namespace App\Payment\Job;

use App\Currency\Model\CurrencyModel as CurrencyModel;
use App\Invoice\Model\InvoiceModel as InvoiceModel;
use App\Payment\PaymentFactory as PaymentFactory;
use App\Payment\Library\PayGateService;
use App\Payment\Library\Payment\PaymentPayRequest;
use App\Payment\Library\Payment\PaymentPayResponse;
use App\Payment\Model\PaymentModel as PaymentModel;
use App\Transaction\TranFactory as TranFactory;
use App\Transaction\Model\TranModel as TranModel;
use App\User\Model\UserModel as UserModel;

class PaymentPay extends \Core\Base\Job
{
	/**
	 * Thong tin tran
	 *
	 * @var TranModel
	 */
	protected $tran;

	/**
	 * Thong tin payment
	 *
	 * @var PaymentModel
	 */
	protected $payment;

	/**
	 * Thong tin nguoi thanh toan
	 *
	 * @var UserModel
	 */
	protected $user;

	/**
	 * Payment amount
	 *
	 * @var float
	 */
	protected $payment_amount;

	/**
	 * Payment fee
	 *
	 * @var float
	 */
	protected $payment_fee;


	/**
	 * Khoi tao doi tuong
	 *
	 * @param TranModel    $tran
	 * @param PaymentModel $payment
	 * @param UserModel    $user
	 */
	public function __construct(TranModel $tran, PaymentModel $payment, UserModel $user)
	{
		$this->tran = $tran;
		$this->payment = $payment;
		$this->user = $user;
	}

	/**
	 * Thuc hien xu ly
	 *
	 * @return PaymentPayResponse
	 */
	public function handle()
	{
		$this->updateTran();

		return $this->dispatchTran();
	}

	/**
	 * Cap nhat thong tin tran
	 */
	protected function updateTran()
	{
		$data = TranFactory::tran()->makeTranDataWithPayment($this->getInvoice(), $this->getPayment());

		$this->tran->update(array_merge($data, [
			'pay_at'         => now(),
			'paying'         => true,
			'pay_request_id' => $this->createPayRequestId(),
		]));
	}

	/**
	 * Gui tran sang paygate
	 *
	 * @return PaymentPayResponse
	 */
	protected function dispatchTran()
	{
		$request = new PaymentPayRequest([
			'amount'     => $this->tran->payment_net,
			'tran'       => $this->tran,
			'user'       => $this->user,
			'token'      => $this->makePaymentToken(),
			'url_result' => $this->urlPaymentResult(),
			'url_notify' => $this->urlPaymentNotify(),
		]);

		return $this->makePaygateService()->paymentPay($request);
	}

	/**
	 * Tao url payment result
	 *
	 * @return string
	 */
	protected function urlPaymentResult()
	{
		return PaymentFactory::service()->urlPaymentResult($this->tran);
	}

	/**
	 * Tao url payment notify
	 *
	 * @return string
	 */
	protected function urlPaymentNotify()
	{
		return PaymentFactory::service()->urlPaymentResult($this->tran, 'notify');
	}

	/**
	 * Tao payment token
	 *
	 * @return string
	 */
	protected function makePaymentToken()
	{
		return PaymentFactory::service()->makePaymentToken($this->tran);
	}

	/**
	 * Tao pay_request_id
	 *
	 * @return string
	 */
	protected function createPayRequestId()
	{
		return random_string('unique');
	}

	/**
	 * Lay doi tuong PayGateService tuong ung cua payment
	 *
	 * @return PayGateService
	 */
	protected function makePaygateService()
	{
		return PaymentFactory::makePaygateService($this->payment->key);
	}

	/**
	 * Lay thong tin tran
	 *
	 * @return TranModel
	 */
	public function getTran()
	{
		return $this->tran;
	}

	/**
	 * Lay thong tin payment
	 *
	 * @return PaymentModel
	 */
	public function getPayment()
	{
		return $this->payment;
	}

	/**
	 * Lay thong tin user
	 *
	 * @return UserModel
	 */
	public function getUser()
	{
		return $this->user;
	}

	/**
	 * Lay thong tin invoice
	 *
	 * @return InvoiceModel
	 */
	public function getInvoice()
	{
		return $this->tran->invoice;
	}

	/**
	 * Lay thong tin currency
	 *
	 * @return CurrencyModel
	 */
	public function getCurrency()
	{
		return $this->payment->currency;
	}
}