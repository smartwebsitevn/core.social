<?php namespace App\StockCard\Model;

use App\Admin\Model\AdminRelationTrait;
use App\Invoice\Model\InvoiceOrderRelationTrait;
use App\Product\Model\ProductRelationTrait;

class StockCardModel extends \Core\Base\Model
{
	use ProductRelationTrait;
	use AdminRelationTrait;
	use InvoiceOrderRelationTrait;

	protected $table = 'stock_card';


	/**
	 * Tao doi tuong moi va gan $attributes
	 *
	 * @param array $attributes
	 * @return static
	 */
	public static function newWithAttributes(array $attributes)
	{
		$relations = static::pullRelationsFromAttributes($attributes, [
			'product'       => 'App\Product\Model\ProductModel',
			'admin'         => 'App\Admin\Model\AdminModel',
			'invoice_order' => 'App\Invoice\Model\InvoiceOrderModel',
		]);

		return parent::newWithAttributes($attributes)->fill($relations);
	}

	/**
	 * Gan card code
	 *
	 * @param string $value
	 */
	protected function setCodeAttribute($value)
	{
		$this->attributes['code'] = security_encrypt($value, 'encode');

		$this->attributes['code_encode'] = md5($value);
	}

	/**
	 * Lay card code
	 *
	 * @param string $value
	 * @return string
	 */
	protected function getCodeAttribute($value)
	{
		return security_encrypt($value, 'decode');
	}

	/**
	 * Lay code_hidden
	 *
	 * @return string
	 */
	protected function getCodeHiddenAttribute()
	{
		$code = $this->getAttribute('code');

		$length = strlen($code);

		$left = floor($length / 2);

		$right = $length - $left;

		return substr($code, 0, $left) . str_repeat('*', $right);
	}

	/**
	 * Kiem tra co the thuc hien hanh dong hay khong
	 *
	 * @param string $action
	 * @return bool
	 */
	public function can($action)
	{
		$status = $this->getAttribute('status');

		switch ($action)
		{
			case 'sold':
			case 'delete':
			{
				return ! $this->getAttribute('sold');
			}

			case 'unsold':
			{
				return $this->getAttribute('sold');
			}
		}

		return parent::can($action);
	}

}