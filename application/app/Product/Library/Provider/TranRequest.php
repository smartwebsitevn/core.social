<?php namespace App\Product\Library\Provider;

use Core\Support\OptionsAccess;

class TranRequest extends OptionsAccess
{
	protected $config = [

		/*
		 * Ma yeu cau
		 */
		'request_id' => [
			'required' => true,
			'cast' => 'string',
		],

	];

	/**
	 * Phan tich key_connection
	 *
	 * @param int    $num_args
	 * @param string $delimiter
	 * @return array
	 */
	public function parseKeyConnection($num_args, $delimiter = '_')
	{
		$args = explode($delimiter, $this->get('key_connection'), $num_args);

		return array_pad($args, $num_args, null);
	}
}