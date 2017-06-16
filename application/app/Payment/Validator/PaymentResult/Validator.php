<?php namespace App\Payment\Validator\PaymentResult;

use App\Payment\PaymentFactory as PaymentFactory;
use App\Payment\Library\PayGateService;
use App\Payment\Library\Payment\PaymentResultRequest;
use App\Payment\Library\Payment\PaymentResultResponse;
use App\Payment\Library\PaymentStatus;
use App\Payment\Validator\PaymentPay\Validator as PaymentPayValidator;
use App\Payment\Validator\PaymentPay\PaymentPayException;
use App\Payment\Model\PaymentModel as PaymentModel;
use App\User\Model\UserModel as UserModel;
use App\Transaction\Model\TranModel as TranModel;

class Validator
{
	/**
	 * Thong tin tran
	 *
	 * @var TranModel
	 */
	protected $tran;

	/**
	 * Trang thanh toan hien tai
	 *
	 * @var string
	 */
	protected $page;

	/**
	 * Ket qua tra ve tu payment
	 *
	 * @var PaymentResultResponse
	 */
	protected $payment_result;


	/**
	 * Khoi tao doi tuong
	 *
	 * @param TranModel $tran
	 */
	public function __construct(TranModel $tran, $page)
	{
		$this->tran = $tran;
		$this->page = $page;
	}

	/**
	 * Thuc hien validate
	 *
	 * @throws PaymentResultException
	 */
	public function validate()
	{
		if ( ! $this->tran->paying)
		{
			$this->throwException(Error::TRAN_INVALID);
		}

		if ( ! $this->getPayment())
		{
		    $this->throwException(Error::PAYMENT_NOT_EXIST);
		}

		$this->validatePaymentPay();

		$this->validatePaymentResult();

		// Kiem tra trang thai thuc te cua giao dich,
		// phong truong hop du lieu da thay doi vi qua trinh xac thuc cua payment mat nhieu thoi gian
		if ( ! $this->checkRealTranStatus())
		{
		    $this->throwException(Error::CAN_NOT_PAY_TRAN);
		}
	}

	/**
	 * Kiem tra cac dieu kien thanh toan giao dich
	 *
	 * @throws PaymentResultException
	 */
	protected function validatePaymentPay()
	{
		try
		{
			$validator = new PaymentPayValidator($this->tran, $this->getPayment(), $this->getUser());

			$validator->validate();
		}
		catch (PaymentPayException $e)
		{
			throw new PaymentResultException($e->getError(), $e->getMessage());
		}
	}

	/**
	 * Kiem tra ket qua tra ve tu payment
	 *
	 * @throws PaymentResultException
	 */
	protected function validatePaymentResult()
	{
		$response = $this->makePaymentResult();

		if ($response->status == PaymentStatus::NONE) return;

		if ($response->status != PaymentStatus::SUCCESS)
		{
			$this->throwException(Error::PAYMENT_RESULT_UNSUCCESSFUL, [
				'error' => $response->error,
			]);
		}

		if ($response->tran_id != $this->tran->id)
		{
			$this->throwException(Error::TRAN_ID_RESULT_INVALID);
		}

		if ($response->amount < $this->tran->payment_net)
		{
			$this->throwException(Error::PAYMENT_AMOUNT_INVALID);
		}
	}

	/**
	 * Goi payment service lay ket qua tra ve tu payment
	 *
	 * @return PaymentResultResponse
	 */
	protected function makePaymentResult()
	{
		$request = new PaymentResultRequest([
			'page'   => $this->page,
			'amount' => $this->tran->payment_net,
			'tran'   => $this->tran,
			'user'   => $this->getUser(),
		]);

		$this->payment_result = $this->makePaygateService()->paymentResult($request);

		return $this->payment_result;
	}

	/**
	 * Kiem tra trang thai thuc te cua giao dich
	 *
	 * @return bool
	 */
	protected function checkRealTranStatus()
	{
		$this->tran->status = TranModel::find($this->tran->id)->status;

		return $this->tran->can('pay');
	}

	/**
	 * Lay doi tuong PayGateService cua payment hien tai
	 *
	 * @return PayGateService
	 */
	protected function makePaygateService()
	{
		return PaymentFactory::makePaygateService($this->getPayment()->key);
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
		return $this->tran->payment;
	}

	/**
	 * Lay thong tin user
	 *
	 * @return UserModel
	 */
	public function getUser()
	{
		return $this->tran->user ?: new UserModel;
	}

	/**
	 * Lay ket qua tra ve tu payment
	 *
	 * @return PaymentResultResponse|null
	 */
	public function getPaymentResult()
	{
		return $this->payment_result;
	}

	/**
	 * Throw exception
	 *
	 * @param string $error
	 * @param array  $replace
	 * @throws PaymentResultException
	 */
	public function throwException($error, $replace = [])
	{
		$message = PaymentFactory::service()->errorLang($error, $replace);

		throw new PaymentResultException($error, $message);
	}

}