<?php namespace App\Product\Library\Provider;

use Core\Support\OptionsAccess;
use Core\Support\Arr;

class TestResponse extends OptionsAccess
{
	protected $config = [

		'status' => [
			'required' => true,
		],

		'errors' => [],

		'content' => [],

	];

	/**
	 * Tao response success
	 *
	 * @param mixed $content
	 * @return static
	 */
	public static function success($content = null)
	{
		return new static([
			'status'  => true,
			'content' => $content,
		]);
	}

	/**
	 * Tao response error
	 *
	 * @param array $errors
	 * @param mixed $content
	 * @return static
	 */
	public static function error($errors, $content = null)
	{
		return new static([
			'status'  => false,
			'errors'  => $errors,
			'content' => $content,
		]);
	}

	/**
	 * Lay errors
	 *
	 * @param array $value
	 * @return array
	 */
	public function getErrorsOption($value)
	{
		return Arr::toArray($value);
	}

}