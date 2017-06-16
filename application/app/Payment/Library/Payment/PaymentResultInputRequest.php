<?php namespace App\Payment\Library\Payment;

use Core\Support\OptionsAccess;

class PaymentResultInputRequest extends OptionsAccess
{
	protected $config = [

		/*
		 * Trang thanh toan tra ve hien tai
		 * $request->page = ressult | notify
		 */
		'page' => [
			'required' => true,
			'cast' => 'string',
		],

	];
}