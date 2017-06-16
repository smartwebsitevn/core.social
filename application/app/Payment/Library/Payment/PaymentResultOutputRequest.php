<?php namespace App\Payment\Library\Payment;

use Core\Support\OptionsAccess;
use App\Transaction\Model\TranModel as TranModel;
use App\Payment\Validator\PaymentResult\Error as PaymentResultError;

class PaymentResultOutputRequest extends OptionsAccess
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
		 * Trang thai xu ly
		 */
		'status' => [
			'required' => true,
			'cast' => 'bool',
		],

		/**
		 * Thong tin giao dich
		 *
		 * @var TranModel
		 */
		'tran' => [],

		/**
		 * Du lieu tra ve tu payment
		 *
		 * @var array
		 */
		'payment_result' => [],

		/**
		 * Loi xu ly (Lay theo PaymentResultError::***)
		 */
		'error' => [
			'cast' => 'string',
		],

		/**
		 * Thong bao loi
		 */
		'message' => [
			'cast' => 'string',
		],

	];
}