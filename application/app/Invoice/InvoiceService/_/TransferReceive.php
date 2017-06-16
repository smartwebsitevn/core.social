<?php namespace App\Invoice\InvoiceService;

use App\Invoice\Library\InvoiceService;
use App\Invoice\Library\ServiceType;
use App\Invoice\Model\InvoiceOrderModel;
use App\Transfer\Model\TransferModel;

class TransferReceive extends InvoiceService
{
	/**
	 * Khoi tao doi tuong
	 */
	public function __construct()
	{
		t('lang')->load('modules/transfer/common');
	}

	/**
	 * Lay loai dich vu (lay theo ServiceType::***)
	 *
	 * @return string
	 */
	public function type()
	{
		return ServiceType::TRANSFER_RECEIVE;
	}

	/**
	 * Lay thong tin
	 *
	 * @return array
	 */
	public function info()
	{
		return [
			'name' => 'Nhận tiền'
		];
	}

	/**
	 * Lay mo ta cua order
	 *
	 * @param InvoiceOrderModel $invoice_order
	 * @return null|string|array
	 */
	public function getOrderDesc(InvoiceOrderModel $invoice_order)
	{
		$options = $invoice_order->order_options;

		$amount = array_get($options, 'amount');

		$currency_id = array_get($options, 'currency_id');

		$sender_purse = array_get($options, 'sender_purse_number');

		$receiver_purse = array_get($options, 'receiver_purse_number');

		$sender = array_get($options, 'sender_username') ?: array_get($options, 'sender_email');

		$receiver = array_get($options, 'receiver_username') ?: array_get($options, 'receiver_email');

		return lang('order_desc_transfer_receive', [
			'amount'         => currency_format_amount($amount, $currency_id),
			'sender_purse'   => $sender_purse,
			'receiver_purse' => $receiver_purse,
			'sender'         => $sender,
			'receiver'       => $receiver,
		]);
	}

	/**
	 * View invoice
	 *
	 * @param InvoiceOrderModel $invoice_order
	 * @return string|null
	 */
	public function view(InvoiceOrderModel $invoice_order)
	{
		$transfer = TransferModel::findByReceiveInvoiceOrder($invoice_order->id);

		if ( ! $transfer) return;

		$data = compact('transfer');

		return view('tpl::transfer/view/receive', $data, true);
	}

}