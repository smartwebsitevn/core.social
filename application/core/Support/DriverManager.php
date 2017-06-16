<?php namespace Core\Support;

use Core\App\App;

abstract class DriverManager
{
	/**
	 * Danh sach driver
	 *
	 * @var array
	 */
	protected $list;


	/**
	 * Lay danh sach drivers
	 *
	 * @return array
	 */
	abstract protected function getListDrivers();

	/**
	 * Lay class cua driver
	 *
	 * @param string $key
	 * @return string
	 */
	abstract protected function getDriverClass($key);

	/**
	 * Lay doi tuong cua driver
	 *
	 * @param string $key
	 * @return mixed
	 */
	public function make($key)
	{
		$class = $this->getDriverClass($key);

		if ( ! App::app()->bound($class))
		{
			App::app()->singleton($class, function() use ($key, $class)
			{
				return $this->newInstance($key, $class);
			});
		}

		return App::app()->make($class);
	}

	/**
	 * Tao doi tuong cua driver
	 *
	 * @param string $key
	 * @param string $class
	 * @return mixed
	 */
	protected function newInstance($key, $class)
	{
		return new $class;
	}

	/**
	 * Kiem tra su ton tai cua driver
	 *
	 * @param string $key
	 * @return bool
	 */
	public function has($key)
	{
		return in_array($key, $this->lists(), true);
	}

	/**
	 * Lay danh sach drivers
	 *
	 * @return array
	 */
	public function lists()
	{
		if (is_null($this->list))
		{
			$this->list = $this->getListDrivers();
		}

		return $this->list;
	}

}