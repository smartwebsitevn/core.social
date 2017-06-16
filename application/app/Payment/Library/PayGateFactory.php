<?php namespace App\Payment\Library;

use Core\Support\DriverInstallableBase;
use App\Payment\Model\PayGateModel as PayGateModel;
use App\Payment\Model\PaymentModel as PaymentModel;
use App\Payment\Library\PayGateService;

abstract class PayGateFactory extends DriverInstallableBase
{
	/**
	 * Lay type cua driver
	 *
	 * @return string
	 */
	protected function getDriverType()
	{
		return 'PayGate';
	}

	/**
	 * Tao class name
	 *
	 * @param string $name
	 * @return string
	 */
	public function makeClassName($name)
	{
		$name = str_replace('/', '\\', $name);

		return 'App\Payment\PayGate\\'.$this->key().'\\'.$name;
	}

	/**
	 * Tao doi tuong PayGateService
	 *
	 * @param PaymentModel $payment
	 * @return PayGateService
	 */
	public function makeService(PaymentModel $payment)
	{
		$class = $this->makeClassName('Service');

		return new $class($this, $payment);
	}

	/**
	 * Callback khi cai dat
	 *
	 * @param PayGateModel $paygate
	 */
	public function onInstall(PayGateModel $paygate){}

	/**
	 * Callback khi go bo
	 *
	 * @param PayGateModel $paygate
	 */
	public function onUninstall(PayGateModel $paygate){}

}