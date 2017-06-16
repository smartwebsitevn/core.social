<?php namespace Core\RequestHandler;

use Core\App\App;
use Core\Support\Traits\PropertyAccessTrait;
use Core\Support\Str;

class RequestHandler
{
	use PropertyAccessTrait;


	/**
	 * Khoi tao doi tuong
	 *
	 * @param array $args
	 */
	public function __construct(array $args = [])
	{
		$this->set($args);
	}

	/**
	 * Tao doi tuong moi
	 *
	 * @param array $args
	 * @return static
	 */
	public static function make(array $args = [])
	{
		return new static($args);
	}

	/**
	 * Thuc hien xu ly
	 */
	public function run(){}

	/**
	 * Goi callback
	 *
	 * @param mixed $callback
	 * @param array $args
	 * @return mixed
	 */
	protected function call($callback, array $args = [])
	{
		if ( ! $callback) return;

		if (is_callable($callback))
		{
			return call_user_func_array($callback, $args);
		}

		if (is_string($callback) && str_contains($callback, '@'))
		{
			list($object, $method) = Str::parseNamespace($callback, '@');

			return call_user_func_array([App::app()->make($object), $method], $args);
		}
	}

}