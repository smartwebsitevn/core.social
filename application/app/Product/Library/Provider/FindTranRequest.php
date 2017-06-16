<?php namespace App\Product\Library\Provider;

use Core\Support\OptionsAccess;

class FindTranRequest extends OptionsAccess
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
		 * Lenh thuc hien
		 */
		'command' => [
			'required' => true,
			'cast' => 'string',
		],

		/*
		 * Du lieu dau vao
		 */
		'input' => [
			'default' => [],
			'allowed_types' => 'array',
		],

		/*
		 * Ma giao dich phat sinh ben nha cung cap
		 */
		'provider_tran_id' => [
			'cast' => 'string',
		],

	];
}