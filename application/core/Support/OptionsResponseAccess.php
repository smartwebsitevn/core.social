<?php namespace Core\Support;

class OptionsResponseAccess extends OptionsAccess
{
	protected $config = [

		'status' => [
			'required' => true,
			'cast' => 'bool',
		],

		'error' => [
			'cast' => 'string',
		],

	];

	/**
	 * Tao response error
	 *
	 * @param string $error
	 * @param array  $response
	 * @return static
	 */
	public static function error($error, array $response = [])
	{
		return new static(array_merge($response, [
			'status' => false,
			'error'  => $error,
		]));
	}

}