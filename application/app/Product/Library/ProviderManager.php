<?php namespace App\Product\Library;

use Core\App\App;
use Core\Base\Model as BaseModel;
use Core\Support\DriverInstallableManager;
use Core\Support\Traits\ServiceCreatorTrait;
use InvalidArgumentException;
use TF\Support\Collection;
use App\Product\Model\ProviderModel as ProviderModel;
use App\Product\Library\ProviderService;

class ProviderManager extends DriverInstallableManager
{
	use ServiceCreatorTrait;

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
		return new ProviderModel($info);
	}

	/**
	 * Lay danh sach du lieu trong database
	 *
	 * @return Collection
	 */
	protected function getListModelData()
	{
		return ProviderModel::all();
	}

	/**
	 * Lay danh sach drivers
	 *
	 * @return array
	 */
	protected function getListDrivers()
	{
		$path = APPPATH.'app/Product/Provider';

		return array_map('basename', App::file()->directories($path));
	}

	/**
	 * Lay class cua driver
	 *
	 * @param string $key
	 * @return string
	 */
	protected function getDriverClass($key)
	{
		return 'App\Product\Provider\\'.$key.'\Factory';
	}

	/**
	 * Tao doi tuong service
	 *
	 * @param string $key
	 * @return ProviderService
	 * @throws InvalidArgumentException
	 */
	protected function createService($key)
	{
		if ( ! $model = $this->data($key))
		{
			throw new InvalidArgumentException("Product provider [{$key}] not found");
		}

		return $this->make($model->key)->makeService($model);
	}

}