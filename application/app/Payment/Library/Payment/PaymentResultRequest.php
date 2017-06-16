<?php namespace App\Payment\Library\Payment;

use Core\Support\OptionsAccess;
use App\Transaction\Model\TranModel as TranModel;
use App\User\Model\UserModel as UserModel;

class PaymentResultRequest extends OptionsAccess
{
	protected $config = [

		/**
		 * Trang thanh toan tra ve hien tai
		 */
		'page' => [
			'required' => true,
			'cast' => 'string',
		],

		/**
		 * So tien can thanh toan (Quy doi theo tien te cua cong thanh toan)
		 */
		'amount' => [
			'required' => true,
			'cast' => 'float',
		],

		/**
		 * Thong tin giao dich
		 *
		 * @var TranModel
		 */
		'tran' => [
			'required' => true,
		],

		/**
		 * Thong tin thanh vien
		 *
		 * @var UserModel
		 */
		'user' => [
			'required' => true,
		],

	];
}