<?php namespace App\Product\Library\Provider;

use Core\Support\OptionsAccess;

class FindTranResponse extends OptionsAccess
{
	protected $config = [

		/*
		 * Trang thai giao dich (Lay theo ProviderTranStatus::***)
		 */
		'status' => [
			'required' => true,
			'cast' => 'string',
		],

		/*
		 * Ma giao dich phat sinh ben nha cung cap
		 */
		'provider_tran_id' => [
			'cast' => 'string',
		],

		/*
		 * Thong tin giao dich phat sinh ben nha cung cap
		 */
		'provider_tran' => [
			'default' => [],
			'allowed_types' => 'array',
		],

		/*
		 * Thong bao tra ve
		 */
		'message' => [
			'cast' => 'string',
		],

	];
}