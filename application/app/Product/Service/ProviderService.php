<?php namespace App\Product\Service;

use App\Product\ProductFactory as ProductFactory;
use App\Product\Model\ProviderModel as ProviderModel;

class ProviderService
{
	/**
	 * Cai dat
	 *
	 * @param string $key
	 * @param array  $data
	 * @return ProviderModel
	 */
	public function install($key, array $data)
	{
		$provider = $this->createProvider($key, $data);

		$provider->providerInstance()->onInstall($provider);

		return $provider;
	}

	/**
	 * Tao Provider
	 *
	 * @param string $key
	 * @param array  $data
	 * @return ProviderModel
	 */
	protected function createProvider($key, array $data)
	{
		$provider = ProductFactory::providerManager()->makeInfo($key);

		$data = array_merge(compact('key'), $this->getDataSync($provider), $data);

		$data = array_add($data, 'sort_order', now());

		return ProviderModel::create($data);
	}

	/**
	 * Chinh sua
	 *
	 * @param ProviderModel $provider
	 * @param array        $data
	 * @return ProviderModel
	 */
	public function edit(ProviderModel $provider, array $data)
	{
		$data = array_merge($this->getDataSync($provider), $data);

		$provider->update($data);

		return $provider;
	}

	/**
	 * Lay du lieu can dong bo
	 *
	 * @param ProviderModel $provider
	 * @return array
	 */
	protected function getDataSync(ProviderModel $provider)
	{
		$version = $provider->providerInstance()->config('version');

		$options = $provider->options;

		$options['config'] = array_except(
			$provider->providerInstance()->config(),
			['name', 'desc', 'version']
		);

		return compact('version', 'options');
	}

	/**
	 * Go bo
	 *
	 * @param ProviderModel $provider
	 */
	public function uninstall(ProviderModel $provider)
	{
		$provider->delete();

		$provider->providerInstance()->onUninstall($provider);
	}

	/**
	 * Dong bo thong tin
	 *
	 * @param array $providers
	 * @return array
	 */
	public function sync($providers = null)
	{
		$providers = $providers ?: ProviderModel::all()->all();

		foreach ($providers as $provider)
		{
			$data = $this->getDataSync($provider);

			$provider->update($data);
		}

		return $providers;
	}

}