<?php namespace App\Invoice\InvoiceService;

use App\Invoice\Library\InvoiceService;
use App\Invoice\Library\ServiceType;
use App\Invoice\Model\InvoiceOrderModel as InvoiceOrderModel;
use App\Deposit\Model\DepositModel as DepositModel;
use App\Deposit\Job\ActiveDepositPayment;

class DepositPayment extends InvoiceService
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
			'name' => 'Nạp tiền',
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

		return lang('order_desc_deposit_payment', [
			'amount' => currency_format_amount($amount, $currency_id),
			'purse'  => array_get($options, 'purse_number'),
		]);
	}

	/**
	 * Kich hoat invoice
	 *
	 * @param InvoiceOrderModel $invoice_order
	 */
	public function active(InvoiceOrderModel $invoice_order)
	{
		$deposit = DepositModel::findByInvoiceOrder($invoice_order->id);

		if ( ! $deposit || ! $deposit->can('active')) return;

		try
		{
			(new ActiveDepositPayment($deposit))->handle();
		}
		catch (\Exception $e){}
	}

}