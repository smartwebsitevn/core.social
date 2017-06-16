<?php namespace App\Product\Model;

use App\Product\ProductFactory as ProductFactory;
use App\Product\Library\ProviderFactory;
use App\Product\Library\ProviderService;

class ProviderModel extends \Core\Model\Extension
{
	/**
	 * Get extension type
	 *
	 * @return string
	 */
	public function getExtensionType()
	{
		return 'ProductProvider';
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
				return ProductFactory::providerManager()->canInstall($key);
			}

			case 'uninstall':
			case 'edit':
			case 'test':
			{
				return ProductFactory::providerManager()->installed($key);
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
				return admin_url('product_provider/install/'.$key, $opt);
			}
		}

		$uri = 'product_provider/'.$action.'/'.$this->getKey();
		$uri = trim($uri, '/');

		return admin_url($uri, $opt);
	}

	/**
	 * Lay doi tuong ProviderFactory
	 *
	 * @return ProviderFactory
	 */
	public function providerInstance()
	{
		$key = $this->getAttribute('key');

		return ProductFactory::provider($key);
	}

	/**
	 * Lay doi tuong ProviderService
	 *
	 * @return ProviderService
	 */
	public function providerServiceInstance()
	{
		$key = $this->getAttribute('key');

		return ProductFactory::providerService($key);
	}

}