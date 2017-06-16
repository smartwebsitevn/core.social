<?php namespace App\Invoice\InvoiceService;

use App\Invoice\Library\InvoiceService;
use App\Invoice\Library\ServiceType;
use App\Invoice\Model\InvoiceOrderModel as InvoiceOrderModel;
use App\Deposit\Model\DepositModel as DepositModel;

class DepositAdmin extends InvoiceService
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
			'name' => 'Nạp tiền trực tiếp'
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

		$admin = get_area() == 'admin' ? array_get($options, 'admin_username') : '';

		return lang('order_desc_deposit_admin', [
			'admin'  => $admin,
			'amount' => currency_format_amount($amount, $currency_id),
			'purse'  => array_get($options, 'purse_number'),
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
		$deposit = DepositModel::findByInvoiceOrder($invoice_order->id);

		if ( ! $deposit) return null;

		$deposit->invoice_order = $invoice_order;

		$data = compact('deposit');

		return view('tpl::deposit_admin/view', $data, true);
	}

}