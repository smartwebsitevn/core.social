<?php namespace Core\Base;

use Core\Support\Arr;

abstract class RequestHandler
{
	/**
	 * Input
	 *
	 * @var array
	 */
	protected $input = [];

	/**
	 * Khoi tao doi tuong
	 *
	 * @param array $input
	 */
	public function __construct(array $input = null)
	{
		$this->input = $input ?: (array) t('input')->get();
	}

	/**
	 * Thuc hien xu ly
	 *
	 * @return mixed
	 */
	abstract public function handle();

	/**
	 * Lay input
	 *
	 * @param string $key
	 * @param mixed  $default
	 * @return mixed
	 */
	public function input($key = null, $default = null)
	{
		return array_get($this->input, $key, $default);
	}

	/**
	 * Lay input cua cac keys
	 *
	 * @param string|array $keys
	 * @return array
	 */
	public function inputOnly($keys)
	{
		return Arr::pick($this->input(), $keys);
	}

}