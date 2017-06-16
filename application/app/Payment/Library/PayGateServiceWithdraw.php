<?php namespace App\Payment\Library;

use Core\Support\Traits\FormMakerTrait;
use App\Payment\Model\PaymentModel as PaymentModel;

class PayGateServiceWithdraw
{
	use FormMakerTrait;

	/**
	 * Doi tuong PayGateService
	 *
	 * @var PayGateService
	 */
	protected $service;


	/**
	 * Khoi tao doi tuong
	 *
	 * @param PayGateService $service
	 */
	public function __construct(PayGateService $service)
	{
		$this->service = $service;
	}

	/**
	 * Load config
	 *
	 * @return array
	 */
	protected function loadConfig()
	{
		return $this->getFactory()->config('withdraw', []);
	}

	/**
	 * Xu ly form confirm data
	 *
	 * @param array $data
	 * @return array|string
	 */
	public function formConfirm(array $data)
	{
		$result = [];

		foreach ($this->config() as $key => $opts)
		{
			$result[$opts['name']] = array_get($data, $key);
		}

		return $result;
	}

	/**
	 * Xu ly view thong tin
	 *
	 * @param array $data
	 * @return array|string
	 */
	public function view(array $data)
	{
		$result = [];

		foreach ($this->config() as $key => $opts)
		{
			$result[$opts['name']] = array_get($data, $key);
		}

		return $result;
	}

	/**
	 * Lay payment setting
	 *
	 * @param string $key
	 * @param mixed  $default
	 * @return mixed
	 */
	protected function paymentSetting($key = null, $default = null)
	{
		return array_get($this->getModel()->setting, $key, $default);
	}

	/**
	 * Lay doi tuong PayGateService
	 *
	 * @return PayGateService
	 */
	public function getService()
	{
		return $this->service;
	}

	/**
	 * Lay doi tuong PayGateFactory
	 *
	 * @return PayGateFactory
	 */
	public function getFactory()
	{
		return $this->service->getFactory();
	}

	/**
	 * Lay doi tuong PaymentModel
	 *
	 * @return PaymentModel
	 */
	public function getModel()
	{
		return $this->service->getModel();
	}

}