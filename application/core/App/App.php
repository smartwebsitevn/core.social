<?php namespace Core\App;

use TF\Container\Container;
use TF\Filesystem\Filesystem;
use Core\Validation\Factory as Validation;

class App
{
	/**
	 * Lay doi tuong App
	 *
	 * @return Container
	 */
	public static function app()
	{
		return t('app');
	}

	/**
	 * Lay doi tuong Validation
	 *
	 * @return Validation
	 */
	public static function validation()
	{
		return static::makeServiceProvider('Core\Validation\Factory');
	}

	/**
	 * Lay doi tuong Filesystem
	 *
	 * @return Filesystem
	 */
	public static function file()
	{
		return static::makeServiceProvider('TF\Filesystem\Filesystem');
	}

	/**
	 * Dang ki va lay doi tuong cua service
	 *
	 * @param  string               $abstract
	 * @param  \Closure|string|null $concrete
	 * @return mixed
	 */
	public static function makeServiceProvider($abstract, $concrete = null)
	{
		static::app()->bindIf($abstract, $concrete, true);

		return static::app()->make($abstract);
	}

}