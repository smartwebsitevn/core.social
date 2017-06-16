<?php namespace Core\Support;

use ArrayAccess;
use ArrayIterator;
use IteratorAggregate;
use JsonSerializable;
use Core\Support\Traits\AttributeMutatorTrait;

class AttributesAccess implements ArrayAccess, JsonSerializable, IteratorAggregate
{
	use AttributeMutatorTrait;

	/**
	 * Attributes data
	 *
	 * @var array
	 */
	protected $attributes = [];

	/**
	 * Kieu du lieu cua cac key attribute
	 *
	 * @var array
	 */
	protected $casts = [];


	/**
	 * Khoi tao doi tuong
	 *
	 * @param array $attributes
	 */
	public function __construct(array $attributes = [])
	{
		$this->fill($attributes);
	}

	/**
	 * Gan cac attributes
	 *
	 * @param array $attributes
	 * @return $this
	 */
	public function fill(array $attributes)
	{
		foreach ($attributes as $key => $value)
		{
			$this->setAttribute($key, $value);
		}

		return $this;
	}

	/**
	 * Gan attribute
	 *
	 * @param string $key
	 * @param mixed  $value
	 */
	public function setAttribute($key, $value)
	{
		if ($this->hasSetMutator($key))
		{
			return $this->callSetMutator($key, $value);
		}

		$value = $this->handleAttributeValueInput($key, $value);

		$this->setAttributeValue($key, $value);
	}

	/**
	 * Xu ly gia tri dau vao cua attribute
	 *
	 * @param string $key
	 * @param mixed  $value
	 * @return mixed
	 */
	protected function handleAttributeValueInput($key, $value)
	{
		return $value;
	}

	/**
	 * Thuc hien gan gia tri cho attribute
	 *
	 * @param string $key
	 * @param mixed  $value
	 */
	protected function setAttributeValue($key, $value)
	{
		$this->attributes[$key] = $value;
	}

	/**
	 * Lay attribute
	 *
	 * @param string $key
	 * @return mixed
	 */
	public function getAttribute($key)
	{
		$value = $this->getAttributeValue($key);

		return $this->handleAttributeValueOutput($key, $value);
	}

	/**
	 * Lay gia tri cua attribute
	 *
	 * @param string $key
	 * @return mixed
	 */
	protected function getAttributeValue($key)
	{
		return isset($this->attributes[$key]) ? $this->attributes[$key] : null;
	}

	/**
	 * Xu ly gia tri dau ra cua attribute
	 *
	 * @param string $key
	 * @param mixed  $value
	 * @return mixed
	 */
	protected function handleAttributeValueOutput($key, $value)
	{
		if ($this->hasGetMutator($key))
		{
			return $this->callGetMutator($key, $value);
		}

		if ($this->hasCast($key))
		{
			$value = $this->castAttribute($key, $value);
		}

		return $value;
	}

	/**
	 * Isset attribute
	 *
	 * @param string $key
	 * @return bool
	 */
	public function issetAttribute($key)
	{
		return ! is_null($this->getAttribute($key));
	}

	/**
	 * Unsset attribute
	 *
	 * @param string $key
	 */
	public function unsetAttribute($key)
	{
		unset($this->attributes[$key]);
	}

	/**
	 * Lay list gia tri cua cac attribute
	 *
	 * @param string|array $keys
	 * @return array
	 */
	public function onlyAttributes($keys)
	{
		$result = [];

		foreach ((array) $keys as $key)
		{
			$result[$key] = $this->getAttribute($key);
		}

		return $result;
	}

	/**
	 * Kiem tra key co duoc gan kieu du lieu hay khong
	 *
	 * @param string $key
	 * @return bool
	 */
	protected function hasCast($key)
	{
		return array_key_exists($key, $this->casts);
	}

	/**
	 * Lay kieu du lieu cua key
	 *
	 * @param string $key
	 * @return string
	 */
	protected function getCastType($key)
	{
		return array_get($this->casts, $key);
	}

	/**
	 * Xu ly gia tri theo kieu du lieu cua key
	 *
	 * @param string $key
	 * @param mixed  $value
	 * @return mixed
	 */
	protected function castAttribute($key, $value)
	{
		switch ($this->getCastType($key))
		{
			case 'int':
			case 'integer':
			{
				return (int) $value;
			}
			case 'real':
			case 'float':
			case 'double':
			{
				return (float) $value;
			}
			case 'string':
			{
				return (string) $value;
			}
			case 'bool':
			case 'boolean':
			{
				return (bool) $value;
			}
		}

		return $value;
	}

	/**
	 * Lay attributes
	 *
	 * @return array
	 */
	public function getAttributes()
	{
		return $this->attributes;
	}

	/**
	 * Lay gia tri cua attributes dang array
	 *
	 * @return array
	 */
	public function attributesToArray()
	{
		return $this->onlyAttributes(array_keys($this->attributes));
	}

	/**
	 * Getter
	 *
	 * @param string $key
	 * @return mixed
	 */
	public function __get($key)
	{
		return $this->getAttribute($key);
	}

	/**
	 * Setter
	 *
	 * @param string $key
	 * @param mixed  $value
	 */
	public function __set($key, $value)
	{
		$this->setAttribute($key, $value);
	}

	/**
	 * Isset
	 *
	 * @param string $key
	 * @return bool
	 */
	public function __isset($key)
	{
		return $this->issetAttribute($key);
	}

	/**
	 * Unsset
	 *
	 * @param string $key
	 */
	public function __unset($key)
	{
		$this->unsetAttribute($key);
	}

	/**
	 * Lay gia tri cua key
	 *
	 * @param  string $key
	 * @return mixed
	 */
	public function offsetGet($key)
	{
		return $this->getAttribute($key);
	}

	/**
	 * Gan gia tri cho key
	 *
	 * @param  string $key
	 * @param  mixed  $value
	 */
	public function offsetSet($key, $value)
	{
		$this->setAttribute($key, $value);
	}

	/**
	 * Kiem tra su ton tai cua key
	 *
	 * @param  string $key
	 * @return bool
	 */
	public function offsetExists($key)
	{
		return $this->issetAttribute($key);
	}

	/**
	 * Xoa gia tri cua key
	 *
	 * @param  string $key
	 */
	public function offsetUnset($key)
	{
		$this->unsetAttribute($key);
	}

	/**
	 * Get the instance to array
	 *
	 * @return array
	 */
	public function toArray()
	{
		return $this->attributesToArray();
	}

	/**
	 * Convert instance to json
	 *
	 * @param  int $options
	 * @return string
	 */
	public function toJson($options = 0)
	{
		return json_encode($this->toArray(), $options);
	}

	/**
	 * Convert instance to string
	 *
	 * @return string
	 */
	public function __toString()
	{
		return $this->toJson();
	}

	/**
	 * Get an iterator for the object
	 *
	 * @return ArrayIterator
	 */
	public function getIterator()
	{
		return new ArrayIterator($this->toArray());
	}

	/**
	 * Convert the object into something JSON serializable.
	 *
	 * @return array
	 */
	public function jsonSerialize()
	{
		return $this->toArray();
	}

}