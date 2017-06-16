<?php namespace App\Product\Model;

use App\File\Model\FileModel as FileModel;
use App\Product\ProductFactory as ProductFactor;
use App\Product\ProductFactory;

class ProductModel extends \Core\Base\Model
{
	protected $table = 'product';

	protected $casts = [
		'price'           => 'float',
		'price_original'  => 'float',
		'price_par'       => 'float',
		'prices_currency' => 'array',
		'discounts'       => 'array',
		'keys_connection' => 'array',
	];

	protected $defaults = [
		'prices_currency' => [],
		'discounts'       => [],
		'keys_connection' => [],
	];

	protected $formats = [
		'price'          => 'amount',
		'price_original' => 'amount',
		'price_par'      => 'amount',
		'price_user'     => 'amount',
		'created'        => 'date',
	];

	/**
	 * Cac column dang contents
	 *
	 * @var array
	 */
	protected $contents_columns = ['desc'];


	/**
	 * Get cat attribute
	 *
	 * @return CatModel|null
	 */
	protected function getCatAttribute()
	{
		$cat_id = $this->getAttribute('cat_id');

		return ProductFactor::cat()->find($cat_id);
	}

	/**
	 * Get provider attribute
	 *
	 * @return mixed
	 */
	protected function getProviderAttribute()
	{
		$provider_key = $this->getAttribute('provider_key');

		return ProductFactor::providerManager()->data($provider_key);
	}

	/**
	 * Lay key ket noi cua provider hien tai (provider_key_connection)
	 *
	 * @return string
	 */
	protected function getProviderKeyConnectionAttribute()
	{
		$provider_key = $this->getAttribute('provider_key');

		$keys_connection = $this->getAttribute('keys_connection');

		return array_get($keys_connection, $provider_key);
	}

	/**
	 * Get image attribute
	 *
	 * @return FileModel
	 */
	protected function getImageAttribute()
	{
		if ( ! array_key_exists('image', $this->additional))
		{
			$image_name = $this->getAttribute('image_name');

			$this->additional['image'] = FileModel::newFromFileName($image_name);
		}

		return $this->additional['image'];
	}

	/**
	 * Set available attribute
	 *
	 * @param int $value
	 */
	protected function setAvailableAttribute($value)
	{
		$this->additional['available'] = $value;
	}

	/**
	 * Lay so luong trong kho
	 *
	 * @return int
	 */
	protected function getAvailableAttribute()
	{
		if ( ! array_key_exists('available', $this->additional))
		{
			$provider_key = $this->getAttribute('provider_key');

			$provider_service = ProductFactory::providerService($provider_key);

			$this->additional['available'] = $provider_service->useStockCard()
				? ProductFactor::product()->getAvailable($this)
				: -1;
		}

		return $this->additional['available'];
	}

	/**
	 * Set price_user attribute
	 *
	 * @param float $value
	 */
	protected function setPriceUserAttribute($value)
	{
		$this->additional['price_user'] = $value;
	}

	/**
	 * Lay gia cua product theo user_group
	 *
	 * @return float
	 */
	protected function getPriceUserAttribute()
	{
		if ( ! array_key_exists('price_user', $this->additional))
		{
			$this->additional['price_user'] = ProductFactor::product()->getPriceUser($this);
		}

		return $this->additional['price_user'];
	}

	/**
	 * Kiem tra co the thuc hien hanh dong hay khong
	 *
	 * @param string $action
	 * @return bool
	 */
	public function can($action)
	{
		switch ($action)
		{
			case 'buy':
			case 'purchase':
			{
				return $this->getAttribute('status') && $this->getAttribute('status_sell');
			}

			case 'feature':
			{
				return true;
			}

			case 'feature_remove':
			{
				return $this->getAttribute('feature');
			}
		}

		return parent::can($action);
	}

}