<?php namespace App\Accountant\Library;

abstract class Reason
{
	/**
	 * Key
	 *
	 * @var string
	 */
	protected $key;

	/**
	 * Options
	 *
	 * @var array
	 */
	protected $options = [];


	/**
	 * Khoi tao doi tuong
	 *
	 * @param array $options
	 */
	public function __construct(array $options)
	{
		$this->options = $options;
	}

	/**
	 * Lay mo ta
	 *
	 * @return string
	 */
	abstract public function desc();

	/**
	 * Lay url chi tiet
	 *
	 * @return string|null
	 */
	public function urlDetail()
	{
		return null;
	}

	/**
	 * Lay admin_url chi tiet
	 *
	 * @return string|null
	 */
	public function adminUrlDetail()
	{
		return null;
	}

	/**
	 * Lay key
	 *
	 * @return string
	 */
	public function key()
	{
		return $this->key ?: class_basename($this);
	}

	/**
	 * Lay options
	 *
	 * @return array
	 */
	public function options()
	{
		return $this->options;
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
		return array_get($this->options(), $key, $default);
	}

}