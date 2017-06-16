<?php namespace App\Product\Library\Cart;

use Core\ShoppingCart\Item as BaseItem;
use App\Product\Model\ProductModel as ProductModel;

class Item extends BaseItem
{
	/**
	 * Gan product
	 *
	 * @param ProductModel $product
	 */
	protected function setProductAttribute(ProductModel $product)
	{
		$this->attributes['product'] = $product;
	}

	/**
	 * Lay product
	 *
	 * @return ProductModel
	 */
	public function getProduct()
	{
		return $this->getAttribute('product');
	}

	/**
	 * Lay profit
	 *
	 * @return float
	 */
	public function getProfit()
	{
		return $this->getAttribute('profit');
	}
}