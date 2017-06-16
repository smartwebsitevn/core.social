<?php namespace App\Payment\Library;

use Core\App\App;
use Core\Base\Model as BaseModel;
use Core\Support\DriverInstallableManager;
use TF\Support\Collection;
use App\Payment\Model\PayGateModel as PayGateModel;

class PayGateManager extends DriverInstallableManager
{
	/**
	 * Lay thong tin tu config
	 *
	 * @param string $key
	 * @return array
	 */
	protected function getInfoData($key)
	{
		return $this->make($key)->info();
	}

	/**
	 * Tao doi tuong model
	 *
	 * @param array $info
	 * @return BaseModel
	 */
	protected function newModelInstance(array $info)
	{
		return new PayGateModel($info);
	}

	/**
	 * Lay danh sach du lieu trong database
	 *
	 * @return Collection
	 */
	protected function getListModelData()
	{
		return PayGateModel::all();
	}

	/**
	 * Lay danh sach drivers
	 *
	 * @return array
	 */
	protected function getListDrivers()
	{
		$path = APPPATH.'app/Payment/PayGate';

		return array_map('basename', App::file()->directories($path));
	}

	/**
	 * Tao class name cua driver
	 *
	 * @param string $key
	 * @return string
	 */
	protected function getDriverClass($key)
	{
		return 'App\Payment\PayGate\\'.$key.'\Factory';
	}

}