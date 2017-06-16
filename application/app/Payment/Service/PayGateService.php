<?php namespace App\Payment\Service;

use App\Payment\PaymentFactory as PaymentFactory;
use App\Payment\Library\PayGateManager;
use App\Payment\Model\PayGateModel as PayGateModel;

class PayGateService
{
	/**
	 * Cai dat
	 *
	 * @param string $key
	 * @param array  $data
	 * @return PayGateModel
	 */
	public function install($key, array $data)
	{
		$paygate = $this->createPayGate($key, $data);

		$paygate->paygateInstance()->onInstall($paygate);

		return $paygate;
	}

	/**
	 * Tao PayGate
	 *
	 * @param string $key
	 * @param array  $data
	 * @return PayGateModel
	 */
	protected function createPayGate($key, array $data)
	{
		$paygate = $this->getPayGateManager()->makeInfo($key);

		$data = array_merge(compact('key'), $this->getDataSync($paygate), $data);

		$data = array_add($data, 'sort_order', now());

		return PayGateModel::create($data);
	}

	/**
	 * Chinh sua
	 *
	 * @param PayGateModel $paygate
	 * @param array        $data
	 * @return PayGateModel
	 */
	public function edit(PayGateModel $paygate, array $data)
	{
		$data = array_merge($this->getDataSync($paygate), $data);

		$paygate->update($data);

		return $paygate;
	}

	/**
	 * Lay du lieu can dong bo
	 *
	 * @param PayGateModel $paygate
	 * @return array
	 */
	protected function getDataSync(PayGateModel $paygate)
	{
		$version = $paygate->paygateInstance()->config('version');

		$options = $paygate->options;

		$options['config'] = array_except(
			$paygate->paygateInstance()->config(),
			['name', 'desc', 'version']
		);

		return compact('version', 'options');
	}

	/**
	 * Go bo
	 *
	 * @param PayGateModel $paygate
	 */
	public function uninstall(PayGateModel $paygate)
	{
		$paygate->delete();

		$paygate->paygateInstance()->onUninstall($paygate);
	}

	/**
	 * Dong bo thong tin
	 *
	 * @param array $paygates
	 * @return array
	 */
	public function sync($paygates = null)
	{
		$paygates = $paygates ?: PayGateModel::all()->all();

		foreach ($paygates as $paygate)
		{
			$data = $this->getDataSync($paygate);

			$paygate->update($data);
		}

		return $paygates;
	}

	/**
	 * Lay doi tuong PayGateManager
	 *
	 * @return PayGateManager
	 */
	protected function getPayGateManager()
	{
		return PaymentFactory::paygateManager();
	}
}