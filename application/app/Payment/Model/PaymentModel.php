<?php namespace App\Payment\Model;

use App\Payment\PaymentFactory as PaymentFactory;
use App\Payment\Library\PayGateService;
use App\Payment\Model\PayGateModel as PayGateModel;
use App\Payment\Library\PayGateFactory;
use App\Currency\CurrencyFactory as CurrencyFactory;
use App\Currency\Model\CurrencyModel as CurrencyModel;
use Core\Model\SettingCryptAttributeMakerTrait;

class PaymentModel extends \Core\Base\Model
{
	use SettingCryptAttributeMakerTrait;

	protected $table = 'payment';

	protected $casts = [
		'currency_id' => 'int',
		'status'      => 'bool',
		'options'     => 'array',
	];

	protected $defaults = [
		'options' => [],
	];


	/**
	 * Lay paygate attribute
	 *
	 * @return PayGateModel
	 */
	protected function getPaygateAttribute()
	{
		$paygate_key = $this->getAttribute('paygate_key');

		return PaymentFactory::paygateManager()->info($paygate_key);
	}

	/**
	 * Lay currency attribute
	 *
	 * @return CurrencyModel|null
	 */
	protected function getCurrencyAttribute()
	{
		$currency_id = $this->getAttribute('currency_id');

		return CurrencyFactory::currency()->find($currency_id);
	}

	/**
	 * Kiem tra co the thuc hien hanh dong hay khong
	 *
	 * @param string $action
	 * @return bool
	 */
	public function can($action)
	{
		$options = $this->getAttribute('options');

		switch ($action)
		{
			case 'payment':
			{
				return (
					$this->paygateServiceInstance()->canPayment()
					&& array_get($options, 'can_'.$action)
				);
			}

			case 'deposit':
			{
				return (
					! $this->paygateServiceInstance()->useBalance()
					&& $this->paygateServiceInstance()->canPayment()
					&& array_get($options, 'can_'.$action)
				);
			}

			case 'withdraw':
			{
				return (
					! $this->paygateServiceInstance()->useBalance()
					&& $this->paygateServiceInstance()->canWithdraw()
					&& array_get($options, 'can_'.$action)
				);
			}
		}

		return parent::can($action);
	}

	/**
	 * Tao doi tuong PayGateFactory
	 *
	 * @return PayGateFactory
	 */
	public function paygateInstance()
	{
		$paygate_key = $this->getAttribute('paygate_key');

		return PaymentFactory::makePaygate($paygate_key);
	}

	/**
	 * Lay doi tuong PayGateService
	 *
	 * @return PayGateService
	 */
	public function paygateServiceInstance()
	{
			$key = $this->getAttribute('key');
		return PaymentFactory::makePaygateService($key);
	}

	/**
	 * Payment co su dung so du de thanh toan hay khong
	 *
	 * @return bool
	 */
	public function paymentByBalance()
	{
		return $this->paygateServiceInstance()->useBalance();
	}

	/**
	 * Payment co su dung view hien thi rieng luc hien thi cong thanh toan hay khong
	 *
	 * @return bool
	 */
	public function paymentUseView()
	{
		return $this->paygateServiceInstance()->useView();
	}
}