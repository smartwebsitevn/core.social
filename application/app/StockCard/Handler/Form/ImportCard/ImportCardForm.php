<?php namespace App\StockCard\Handler\Form\ImportCard;

use App\Product\Model\ProductModel;
use Core\Support\AttributesAccess;

class ImportCardForm extends AttributesAccess
{
	/**
	 * Danh sach attribute bo sung
	 *
	 * @var array
	 */
	protected $additional = [];

	/**
	 * Key luu tru
	 */
	const STORAGE_KEY = 'stock_card.import_card';


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

	/**
	 * Luu du lieu
	 */
	public function save()
	{
		t('session')->set_userdata(static::STORAGE_KEY, $this->attributes);
	}

	/**
	 * Lay thong tin
	 *
	 * @return null|static
	 */
	public static function get()
	{
		$attributes = t('session')->userdata(static::STORAGE_KEY);

		return $attributes ? new static($attributes) : null;
	}

	/**
	 * Xoa du lieu
	 */
	public static function delete()
	{
		t('session')->unset_userdata(static::STORAGE_KEY);
	}

}