<?php namespace Core\Support;

use Core\Support\Arr;

class RequestAccess
{
	/**
	 * Input
	 *
	 * @var array
	 */
	protected $input = [];

	/**
	 * Data
	 *
	 * @var array
	 */
	protected $data = [];

	/**
	 * Khoi tao doi tuong
	 *
	 * @param array $input
	 */
	public function __construct(array $input)
	{
		$this->input = $input;
	}

	/**
	 * Lay input
	 *
	 * @param string $key
	 * @param mixed  $default
	 * @return mixed
	 */
	public function get($key = null, $default = null)
	{
		return array_get($this->input, $key, $default);
	}

	/**
	 * Lay input cua cac keys
	 *
	 * @param string|array $keys
	 * @return array
	 */
	public function only($keys)
	{
		return Arr::pick($this->input, $keys);
	}

	/**
	 * Lay input ngoai tru cac keys
	 *
	 * @param string|array $keys
	 * @return array
	 */
	public function except($keys)
	{
		return array_except($this->input, $keys);
	}
}