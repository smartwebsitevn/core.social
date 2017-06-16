<?php namespace Core\Model;

use Core\Support\Str;

trait AttributeMethodAccessorTrait
{
	/**
	 * Cac method cho phep goi duoi dang bien
	 *
	 * @var array
	 */
	//protected $methods_accessor = [];


	/**
	 * Kiem tra key co phai la thuoc tinh dong hay khong
	 *
	 * @param string $key
	 * @return bool
	 */
	protected function isAttributeMethodAccessor($key)
	{
		if ( ! str_contains($key, ':')) return false;

		list($method, $args) = $this->parseAttributeMethodAccessor($key);

		return $method ? true : false;
	}

	/**
	 * Lay gia tri cua thuoc tinh dong
	 *
	 * @param string $key
	 * @return mixed
	 */
	protected function getAttributeMethodAccessor($key)
	{
		list($method, $args) = $this->parseAttributeMethodAccessor($key);

		if ($method && method_exists($this, $method))
		{
			return call_user_func_array([$this, $method], $args);
		}
	}

	/**
	 * Kiem tra thuoc tinh dong co ton tai gia tri hay khong
	 *
	 * @param string $key
	 * @param mixed  $value
	 * @return bool
	 */
	protected function issetAttributeMethodAccessor($key, &$value = null)
	{
		return (
			$this->isAttributeMethodAccessor($key)
			&& ! is_null($value = $this->getAttributeMethodAccessor($key))
		);
	}

	/**
	 * Phan tich thuoc tinh dong
	 *
	 * @param string $key
	 * @return array
	 */
	protected function parseAttributeMethodAccessor($key)
	{
		list($method, $args) = Str::parseFunction($key);

		if ( ! in_array($method, $this->methods_accessor))
		{
			$method = null;
		}

		return [$method, $args];
	}
}