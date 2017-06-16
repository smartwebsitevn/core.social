<?php namespace App\Payment\Model;

use App\Payment\PaymentFactory as PaymentFactory;
use App\Payment\Library\PayGateFactory;

class PayGateModel extends \Core\Model\Extension
{
	/**
	 * Get extension type
	 *
	 * @return string
	 */
	public function getExtensionType()
	{
		return 'PayGate';
	}

	/**
	 * Lay config attribute
	 *
	 * @param mixed $value
	 * @return array
	 */
	public function getConfigAttribute($value)
	{
		$options = $this->getAttribute('options');

		return array_get($options, 'config', []);
	}

	/**
	 * Kiem tra co the thuc hien hanh dong hay khong
	 *
	 * @param string $action
	 * @return bool
	 */
	public function can($action)
	{
		$key = $this->getAttribute('key');

		switch ($action)
		{
			case 'install':
			{
				return PaymentFactory::paygateManager()->canInstall($key);
			}

			case 'uninstall':
			case 'edit':
			{
				return PaymentFactory::paygateManager()->installed($key);
			}
		}

		return parent::can($action);
	}

	/**
	 * Tao admin url
	 *
	 * @param string $action
	 * @param array  $opt
	 * @return string
	 */
	public function adminUrl($action, array $opt = array())
	{
		$key = $this->getAttribute('key');

		switch ($action)
		{
			case 'install':
			{
				return admin_url('payment_paygate/install/'.$key, $opt);
			}
		}

		$uri = 'payment_paygate/'.$action.'/'.$this->getKey();
		$uri = trim($uri, '/');

		return admin_url($uri, $opt);
	}

	/**
	 * Tao doi tuong PayGateFactory
	 *
	 * @return PayGateFactory
	 */
	public function paygateInstance()
	{
		$key = $this->getAttribute('key');

		return PaymentFactory::makePaygate($key);
	}

}