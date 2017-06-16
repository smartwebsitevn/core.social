<?php namespace Core\Base;

use Core\App\App;

abstract class Factory
{
	/**
	 * Lay namespace root
	 *
	 * @return string
	 */
	abstract public function getRootNamespace();

	/**
	 * Tao doi tuong service
	 *
	 * @param string $service
	 * @return mixed
	 */
	public static function makeService($service)
	{
		return static::makeServiceProvider('Service/'.$service);
	}

	/**
	 * Dang ki va lay doi tuong cua service
	 *
	 * @param  string               $name
	 * @param  \Closure|string|null $concrete
	 * @return mixed
	 */
	public static function makeServiceProvider($name, $concrete = null)
	{
		$class = static::makeClassName($name);

		return App::makeServiceProvider($class, $concrete);
	}

	/**
	 * Tao class name
	 *
	 * @param string $name
	 * @return string
	 */
	public static function makeClassName($name)
	{
		$name = str_replace('/', '\\', $name);

		return (new static)->getRootNamespace().'\\'.$name;
	}

}