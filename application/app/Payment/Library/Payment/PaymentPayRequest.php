<?php namespace App\Payment\Library\Payment;

use Core\Support\OptionsAccess;
use App\Transaction\Model\TranModel as TranModel;
use App\User\Model\UserModel as UserModel;

class PaymentPayRequest extends OptionsAccess
{
	protected $config = [

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

		/**
		 * Token cua phien thanh toan
		 */
		'token' => [
			'required' => true,
			'cast' => 'string',
		],

		/**
		 * Url nhan ket qua giao dich tra ve
		 */
		'url_result' => [
			'required' => true,
			'cast' => 'string',
		],

		/**
		 * Url nhan thong bao trang thai giao dich
		 */
		'url_notify' => [
			'required' => true,
			'cast' => 'string',
		],

	];
}