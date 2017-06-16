<?php namespace Core\ShoppingCart;

use Core\Support\AttributesAccess;
use InvalidArgumentException;

class Item extends AttributesAccess
{
	/**
	 * Kieu du lieu cua cac key attribute
	 *
	 * @var array
	 */
	protected $casts = [
		'price'    => 'float',
		'quantity' => 'float',
		'amount'   => 'float',
	];

	/**
	 * Item id increment
	 *
	 * @var int
	 */
	protected static $id_increment = 1;


	/**
	 * Khoi tao doi tuong
	 *
	 * @param array $attributes
	 * 	Bao gom cac key:
	 * 		'product'	Thong tin san pham
	 * 		'price'		Gia
	 * 		'quantity'	So luong
	 * 		'amount'	Thanh tien
	 * 		'options'	Cac tuy chon
	 */
	public function __construct(array $attributes = [])
	{
		foreach (['product', 'price', 'quantity', 'amount'] as $key)
		{
			if ( ! isset($attributes[$key]))
			{
				throw new InvalidArgumentException("The param [{$key}] is required");
			}
		}

		$attributes['id'] = static::newId();

		parent::__construct($attributes);
	}

	/**
	 * Tao id moi
	 *
	 * @return int
	 */
	protected static function newId()
	{
		$id = static::$id_increment;

		static::$id_increment++;

		return $id;
	}

	/**
	 * Gan options
	 *
	 * @param array $options
	 */
	protected function setOptionsAttribute(array $options)
	{
		$this->attributes['options'] = $options;
	}

	/**
	 * Get options attribute
	 *
	 * @param array $value
	 * @return array
	 */
	protected function getOptionsAttribute($value)
	{
		return $value ?: [];
	}

	/**
	 * Lay id
	 *
	 * @return int
	 */
	public function getId()
	{
		return $this->getAttribute('id');
	}

	/**
	 * Lay product
	 *
	 * @return mixed
	 */
	public function getProduct()
	{
		return $this->getAttribute('product');
	}

	/**
	 * Lay price
	 *
	 * @return float
	 */
	public function getPrice()
	{
		return $this->getAttribute('price');
	}

	/**
	 * Lay quantity
	 *
	 * @return int|float
	 */
	public function getQuantity()
	{
		return $this->getAttribute('quantity');
	}

	/**
	 * Lay amount
	 *
	 * @return float
	 */
	public function getAmount()
	{
		return $this->getAttribute('amount');
	}

	/**
	 * Lay options
	 *
	 * @return array
	 */
	public function getOptions()
	{
		return $this->getAttribute('options');
	}

	/**
	 * Lay option
	 *
	 * @param string $key
	 * @param mixed  $default
	 * @return mixed
	 */
	public function getOption($key, $default = null)
	{
		return array_get($this->getOptions(), (string) $key, $default);
	}

}