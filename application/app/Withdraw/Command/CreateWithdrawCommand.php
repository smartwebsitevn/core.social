<?php namespace App\Withdraw\Command;

use Core\Support\OptionsAccess;
use App\Invoice\Library\OrderStatus;
use App\Invoice\Model\InvoiceOrderModel as InvoiceOrderModel;
use App\Purse\Model\PurseModel as PurseModel;
use App\Payment\Model\PaymentModel as PaymentModel;

class CreateWithdrawCommand extends OptionsAccess
{
	protected $config = [

		/**
		 * Thong tin invoice_order
		 *
		 * @var InvoiceOrderModel
		 */
		'invoice_order' => [
			'required' => true,
		],

		/**
		 * Thong tin purse
		 *
		 * @var PurseModel
		 */
		'purse' => [
			'required' => true,
		],

		/**
		 * Trang thai
		 *
		 * @var string
		 */
		'status' => [
			'default' => OrderStatus::PENDING,
		],

	];

}