<?php namespace App\Deposit\Library;

use Core\Support\OptionsAccess;
use App\Invoice\Library\OrderStatus;
use App\Invoice\Model\InvoiceOrderModel as InvoiceOrderModel;
use App\Purse\Model\PurseModel as PurseModel;

class CreateDepositOptions extends OptionsAccess
{
	protected $config = [

		/**
		 * Doi tuong invoice_order
		 *
		 * @var InvoiceOrderModel
		 */
		'invoice_order' => [
			'required' => true,
		],

		/**
		 * Doi tuong purse
		 *
		 * @var PurseModel
		 */
		'purse' => [
			'required' => true,
		],

		/**
		 * So tien nap cho purse (tinh theo tien te cua purse)
		 *
		 * @var float
		 */
		'amount' => [
			'required' => true,
			'cast' => 'float',
		],

		/**
		 * Trang thai
		 *
		 * @var string
		 */
		'status' => [
			'default' => OrderStatus::PENDING,
		],

		/**
		 * Options
		 *
		 * @var array
		 */
		'options' => [
			'default' => [],
			'allowed_types' => 'array',
		],

	];
}