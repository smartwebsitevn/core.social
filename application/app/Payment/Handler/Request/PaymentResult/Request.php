<?php namespace App\Payment\Handler\Request\PaymentResult;

use App\Payment\Library\Payment\PaymentResultInputRequest;
use App\Payment\PaymentFactory as PaymentFactory;
use App\Payment\Library\PayGateService;
use App\Payment\Library\PaymentManager;
use App\Payment\Library\Payment\PaymentResultInputResponse;
use App\Payment\Model\PaymentModel as PaymentModel;
use App\Transaction\Model\TranModel as TranModel;
use Core\Support\RequestAccess;

class Request extends RequestAccess
{
	/**
	 * Khoi tao doi tuong
	 *
	 * @param array $input
	 *    $input = [
	 * 		'page' => '',
	 * 		'tran_id' => '',
	 * 		'token' => '',
	 * 		'payment_key' => ''
	 *    ];
	 */
	public function __construct(array $input)
	{
		$this->input = $input;
	}

	/**
	 * Khoi tao request
	 */
	public function init()
	{
		if ($this->useCustomRequest())
		{
			$this->applyCustomRequest();
		}
	}

	/**
	 * Kiem tra payment co su dung request rieng hay khong
	 *
	 * @return bool
	 */
	protected function useCustomRequest()
	{
		return (
			$this->paymentIsActive()
			&& ! is_null($this->getPaymentResultInput())
		);
	}

	/**
	 * Thuc hien gan request rieng cua payment
	 */
	protected function applyCustomRequest()
	{
		$input = $this->getPaymentResultInput()->only(['tran_id', 'token']);

		$this->input = array_merge($this->input, $input);
	}

	/**
	 * Kiem tra payment hien tai co dang active hay khong
	 *
	 * @return bool
	 */
	protected function paymentIsActive()
	{
		return (
			($payment_key = $this->get('payment_key'))
			&& $this->paymentManager()->isActive($payment_key)
		);
	}

	/**
	 * Lay payment result input
	 *
	 * @return PaymentResultInputResponse|null
	 */
	protected function getPaymentResultInput()
	{
		if ( ! array_key_exists('payment_result_input', $this->data))
		{
			$request = new PaymentResultInputRequest($this->only('page'));

			$this->data['payment_result_input'] = $this->makePaygateService()->paymentResultInput($request);
		}

		return $this->data['payment_result_input'];
	}

	/**
	 * Lay doi tuong PayGateService cua payment hien tai
	 *
	 * @return PayGateService
	 */
	protected function makePaygateService()
	{
		return PaymentFactory::makePaygateService($this->get('payment_key'));
	}

	/**
	 * Lay thong tin tran
	 *
	 * @return TranModel|null
	 */
	public function getTran()
	{
		if ( ! array_key_exists('tran', $this->data))
		{
		    $this->data['tran'] = TranModel::find($this->get('tran_id'));
		}

		return $this->data['tran'];
	}

	/**
	 * Lay thong tin payment
	 *
	 * @return PaymentModel|null
	 */
	public function getPayment()
	{
		if ($this->paymentIsActive())
		{
		    return $this->paymentManager()->find($this->get('payment_key'));
		}

		if (
			($key = data_get($this->getTran(), 'payment_key'))
			&& $this->paymentManager()->isActive($key)
		)
		{
			return $this->paymentManager()->find($key);
		}

		return null;
	}

	/**
	 * Lay doi tuong PaymentManager
	 *
	 * @return PaymentManager
	 */
	protected function paymentManager()
	{
		return PaymentFactory::paymentManager();
	}

}