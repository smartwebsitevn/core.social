<?php namespace App\Product\Model;

trait ProductRelationTrait
{
	/**
	 * Gan product
	 *
	 * @param ProductModel $product
	 */
	protected function setProductAttribute(ProductModel $product)
	{
		$this->additional['product'] = $product;
	}

	/**
	 * Lay thong tin san pham
	 *
	 * @return ProductModel|null
	 */
	protected function getProductAttribute()
	{
		if ( ! array_key_exists('product', $this->additional))
		{
			$product_id = $this->getAttribute('product_id');

			$this->additional['product'] = ProductModel::find($product_id);
		}

		return $this->additional['product'];
	}

}