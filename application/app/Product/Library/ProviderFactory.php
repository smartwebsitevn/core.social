<?php namespace App\Product\Library;

use Core\Support\DriverInstallableBase;
use App\Product\Model\ProviderModel as ProviderModel;

abstract class ProviderFactory extends DriverInstallableBase
{
	/**
	 * Lay type cua driver
	 *
	 * @return string
	 */
	protected function getDriverType()
	{
		return 'ProductProvider';
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

		return 'App\Product\Provider\\'.$this->key().'\\'.$name;
	}

	/**
	 * Tao doi tuong ProviderService
	 *
	 * @param ProviderModel $provider
	 * @return ProviderService
	 */
	public function makeService(ProviderModel $provider)
	{
		$class = $this->makeClassName('Service');

		return new $class($this, $provider);
	}

	/**
	 * Callback khi cai dat
	 *
	 * @param ProviderModel $provider
	 */
	public function onInstall(ProviderModel $provider){}

	/**
	 * Callback khi go bo
	 *
	 * @param ProviderModel $provider
	 */
	public function onUninstall(ProviderModel $provider){}

}