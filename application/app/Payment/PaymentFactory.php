<?php namespace App\Payment;

use App\Payment\Library\PayGateManager;
use App\Payment\Library\PayGateFactory as AbstractPayGateFactory;
use App\Payment\Library\PayGateService as AbstractPayGateService;
use App\Payment\Library\PaymentManager;
use App\Payment\Service\PayGateService as PayGateService;
use App\Payment\Service\PaymentService as PaymentService;

class PaymentFactory extends \Core\Base\Factory
{
	/**
	 * Lay namespace root
	 *
	 * @return string
	 */
	public function getRootNamespace()
	{
		return __NAMESPACE__;
	}

	/**
	 * Lay doi tuong service chinh
	 *
	 * @return Service
	 */
	public static function service()
	{
		return static::makeServiceProvider('Service');
	}

	/**
	 * Lay doi tuong PayGateService
	 *
	 * @return PayGateService
	 */
	public static function paygate()
	{
		return static::makeService('PayGateService');
	}

	/**
	 * Lay doi tuong PaymentService
	 *
	 * @return PaymentService
	 */
	public static function payment()
	{
		return static::makeService('PaymentService');
	}

	/**
	 * Lay doi tuong PayGateManager
	 *
	 * @return PayGateManager
	 */
	public static function paygateManager()
	{
		return static::makeServiceProvider('Library/PayGateManager');
	}

	/**
	 * Lay doi tuong PayGateFactory
	 *
	 * @param string $paygate_key
	 * @return AbstractPayGateFactory
	 */
	public static function makePaygate($paygate_key)
	{
		return static::paygateManager()->make($paygate_key);
	}

	/**
	 * Lay doi tuong PaymentManager
	 *
	 * @return PaymentManager
	 */
	public static function paymentManager()
	{
		return static::makeServiceProvider('Library/PaymentManager');
	}

	/**
	 * Lay doi tuong PayGateService tuong ung cua payment
	 *
	 * @param string $payment_key
	 * @return AbstractPayGateService
	 */
	public static function makePaygateService($payment_key)
	{
		return static::paymentManager()->service($payment_key);
	}

}