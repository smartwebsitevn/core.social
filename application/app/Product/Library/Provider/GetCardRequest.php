<?php namespace App\Product\Library\Provider;

use Core\Support\OptionsAccess;

class GetCardRequest extends OptionsAccess
{
	protected $config = [

		/*
		 * Ma yeu cau
		 */
		'request_id' => [
			'required' => true,
			'cast' => 'string',
		],

		/*
		 * Key ket noi
		 */
		'key_connection' => [
			'required' => true,
			'cast' => 'string',
		],

		/*
		 * So luong
		 */
		'quantity' => [
			'required' => true,
			'cast' => 'int',
		],

		/*
		 * Ma giao dich phat sinh ben nha cung cap
		 */
		'provider_tran_id' => [
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
		$args = explode($delimiter, $this->get('key_connection'));

		return array_pad($args, $num_args);
	}
}