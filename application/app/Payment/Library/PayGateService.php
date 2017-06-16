<?php namespace App\Payment\Library;

use App\Payment\Library\Payment\PaymentPayRequest;
use App\Payment\Library\Payment\PaymentPayResponse;
use App\Payment\Library\Payment\PaymentResultInputRequest;
use App\Payment\Library\Payment\PaymentResultInputResponse;
use App\Payment\Library\Payment\PaymentResultOutputRequest;
use App\Payment\Library\Payment\PaymentResultOutputResponse;
use App\Payment\Library\Payment\PaymentResultRequest;
use App\Payment\Library\Payment\PaymentResultResponse;
use App\Payment\Model\PaymentModel as PaymentModel;

abstract class PayGateService
{
	/**
	 * Doi tuong PayGateFactory
	 *
	 * @var PayGateFactory
	 */
	protected $factory;

	/**
	 * Doi tuong PaymentModel
	 *
	 * @var PaymentModel
	 */
	protected $model;

	/**
	 * Doi tuong PayGateServiceWithdraw
	 *
	 * @var PayGateServiceWithdraw
	 */
	protected $withdraw;


	/**
	 * PayGateService constructor.
	 *
	 * @param PayGateFactory $factory
	 * @param PaymentModel   $model
	 */
	public function __construct(PayGateFactory $factory, PaymentModel $model)
	{
		$this->factory = $factory;

		$this->model = $model;
		
		$this->CI =& get_instance();
	}

	/**
	 * Co the thuc hien thanh toan qua payment hay khong
	 *
	 * @return bool
	 */
	public function canPayment()
	{
		return true;
	}

	/**
	 * Co the thuc hien rut tien qua payment hay khong
	 *
	 * @return bool
	 */
	public function canWithdraw()
	{
		return false;
	}

	/**
	 * Payment co su dung so du de thanh toan hay khong
	 *
	 * @return bool
	 */
	public function useBalance()
	{
		return false;
	}

	/**
	 * Payment co su dung view hien thi rieng luc hien thi cong thanh toan hay khong
	 *
	 * @return bool
	 */

	public function useView()
	{
		return false;
	}
	/**
	 * Thuc hien thanh toan
	 *
	 * @param PaymentPayRequest $request
	 * @return PaymentPayResponse
	 */
	public function paymentPay(PaymentPayRequest $request)
	{
		return PaymentPayResponse::content('This function has not been undefined');
	}

	/**
	 * Xu ly ket qua thanh toan tra ve
	 *
	 * @param PaymentResultRequest $request
	 * @return PaymentResultResponse
	 */
	public function paymentResult(PaymentResultRequest $request)
	{
		return new PaymentResultResponse([
			'status' => PaymentStatus::FAILED,
			'error'  => 'This function has not been undefined',
		]);
	}

	/**
	 * Lay payment result input
	 *
	 * @param PaymentResultInputRequest $request
	 * @return PaymentResultInputResponse|null
	 */
	public function paymentResultInput(PaymentResultInputRequest $request)
	{
		return NULL;
	}

	/**
	 * Tao payment result output
	 *
	 * @param PaymentResultOutputRequest $request
	 * @return PaymentResultOutputResponse|null
	 */
	public function paymentResultOutput(PaymentResultOutputRequest $request)
	{
		return null;
	}

	/**
	 * L?u t?t c? thông tin t? c?ng thanh toán tr? v?
	 *
	 * @return PaymentModel
	 */
	protected  function paymentResultOutputSave($tran_id, $data)
	{
		model('tran')->update($tran_id, array('payment_result' => @json_encode($data)));
	}
	/**
	 * Xu ly xem thong tin giao dich phat sinh ben cong thanh toan
	 *
	 * @param array $payment_tran
	 * @return string|array
	 */
	public function viewPaymentTran(array $payment_tran)
	{
		return $payment_tran;
	}

	/**
	 * Lay doi tuong PayGateServiceWithdraw
	 *
	 * @return PayGateServiceWithdraw
	 */
	public function withdraw()
	{
		if (is_null($this->withdraw))
		{
			$class = $this->factory->makeClassName('Withdraw');

		    $this->withdraw = new $class($this);
		}

		return $this->withdraw;
	}

	/**
	 * Lay setting
	 *
	 * @param string $key
	 * @param mixed  $default
	 * @return mixed
	 */
	protected function setting($key = null, $default = null)
	{
		return array_get($this->model->setting, $key, $default);
	}

	/**
	 * Lay doi tuong PayGateFactory
	 *
	 * @return PayGateFactory
	 */
	public function getFactory()
	{
		return $this->factory;
	}

	/**
	 * Lay doi tuong PaymentModel
	 *
	 * @return PaymentModel
	 */
	public function getModel()
	{
		return $this->model;
	}



}