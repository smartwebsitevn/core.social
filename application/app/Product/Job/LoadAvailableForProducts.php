<?php namespace App\Product\Job;

use App\Product\Model\ProductModel;
use App\Product\ProductFactory;

class LoadAvailableForProducts extends \Core\Base\Job
{
	/**
	 * Danh sach san pham
	 *
	 * @var array
	 */
	protected $products;

	/**
	 * So luong ton trong kho cua cac san pham
	 *
	 * @var array
	 */
	protected $availables;


	public static function _t()
	{
		$products = [ProductModel::find(1), ProductModel::find(2)];

		$me = new static($products);

		$v = array_pluck($me->handle(), 'available', 'id');

		pr($v, 0);
	}

	/**
	 * Khoi tao doi tuong
	 *
	 * @param array $products
	 */
	public function __construct(array $products)
	{
		$this->products = $products;
	}

	/**
	 * Thuc hien xu ly
	 *
	 * @return array
	 */
	public function handle()
	{
		foreach ($this->products as $product)
		{
			$product->available = $this->getAvailable($product->id);
		}

		return $this->products;
	}

	/**
	 * Lay available
	 *
	 * @param int $product_id
	 * @return int
	 */
	protected function getAvailable($product_id)
	{
		return array_get($this->getAvailables(), (int) $product_id, -1);
	}

	/**
	 * Lay availables
	 *
	 * @return array
	 */
	protected function getAvailables()
	{
		if (is_null($this->availables))
		{
			$this->availables = [];

			$providers = collect($this->products)->groupBy('provider_key');

			foreach ($providers as $provider_key => $products)
			{
				$availables = ProductFactory::providerService($provider_key)->getAvailables($products);

				foreach ($availables as $product_id => $available)
				{
					$this->availables[$product_id] = $available;
				}
			}
		}

		return $this->availables;
	}

}