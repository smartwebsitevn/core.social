<?php namespace App\Invoice\InvoiceService;

use App\Deposit\Model\DepositCardModel;
use App\Invoice\Library\InvoiceService;
use App\Invoice\Library\ServiceType;
use App\Invoice\Model\InvoiceOrderModel;

class DepositCard extends InvoiceService
{
	/**
	 * Khoi tao doi tuong
	 */
	public function __construct()
	{
		t('lang')->load('modules/deposit/common');
	}

	/**
	 * Lay loai dich vu (lay theo ServiceType::***)
	 *
	 * @return string
	 */
	public function type()
	{
		return ServiceType::DEPOSIT;
	}

	/**
	 * Lay thong tin
	 *
	 * @return array
	 */
	public function info()
	{
		return [
			'name' => 'Đổi thẻ cào'
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

		$user = array_get($options, 'user_username') ?: array_get($options, 'user_email');

		return lang('order_desc_deposit_card', [
			'amount'      => currency_format_amount($amount, $currency_id),
			'purse'       => array_get($options, 'purse_number'),
			'card_type'   => array_get($options, 'card_type_name'),
			'card_amount' => number_format(array_get($options, 'card_amount')),
			'provider'    => array_get($options, 'provider'),
			'user'        => $user,
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
		$deposit = DepositCardModel::findByInvoiceOrder($invoice_order->id);

		if ( ! $deposit) return null;

		t('lang')->load('modules/deposit/deposit_card');

		$deposit->invoice_order = $invoice_order;

		$data = compact('deposit');

		return view('tpl::deposit_card/view', $data, true);
	}


}