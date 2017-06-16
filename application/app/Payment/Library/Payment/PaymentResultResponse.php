<?php namespace App\Payment\Library\Payment;

use Core\Support\OptionsAccess;

class PaymentResultResponse extends OptionsAccess
{
	protected $config = [

		/**
		 * Trang thai thanh toan (Lay theo PaymentStatus::***)
		 */
		'status' => [
			'required' => true,
			'cast' => 'string',
		],

		/**
		 * So tien da thanh toan (Quy doi theo tien te cua cong thanh toan)
		 */
		'amount' => [
			'cast' => 'float',
		],

		/**
		 * Ma giao dich cua he thong
		 */
		'tran_id' => [
			'default' => 0,
		],

		/**
		 * Ma giao dich phat sinh ben cong thanh toan
		 */
		'payment_tran_id' => [
			'cast' => 'string',
		],

		/**
		 * Thong tin giao dich phat sinh ben cong thanh toan
		 *
		 * @var array
		 */
		'payment_tran' => [
			'default' => [],
			'allowed_types' => 'array',
		],

		/**
		 * Loi giao dich
		 */
		'error' => [
			'cast' => 'string',
		],

	];
}