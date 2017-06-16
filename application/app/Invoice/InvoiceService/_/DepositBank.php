<?php namespace App\Invoice\InvoiceService;

use App\Invoice\Library\InvoiceService;
use App\Invoice\Library\ServiceType;
use App\Invoice\Model\InvoiceOrderModel as InvoiceOrderModel;
use App\Payment\Model\PaymentModel;
use App\Payment\PaymentFactory as PaymentFactory;
use App\DepositBank\Model\DepositBankModel;

class DepositBank extends InvoiceService
{
	/**
	 * Khoi tao doi tuong
	 */
	public function __construct()
	{
		t('lang')->load('modules/deposit_bank/deposit_bank');
		t('lang')->load('site/deposit_bank');
	}

	/**
	 * Lay loai dich vu (lay theo ServiceType::***)
	 *
	 * @return string
	 */
	public function type()
	{
		return 'deposit_bank';//ServiceType::RENEWMOVIE;
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
		return lang('order_desc_deposit_bank', [
			'type'        => array_get($options, 'type'),
			'bank'        => array_get($options, 'bank'),
		]);
	    return '';
	}
	
	/**
	 * Lay thong tin
	 *
	 * @return array
	 */
	public function info()
	{
		return [
			'name' => 'Thông báo chuyển tiền',
		];
	}

	/**
	 * View invoice
	 *
	 * @param InvoiceOrderModel $invoice_order
	 * @return string|null
	 */

	public function view(InvoiceOrderModel $invoice_order)
	{

		$order = DepositBankModel::findByInvoiceOrder($invoice_order->id);

		if ( ! $order) return null;

		$order->invoice_order = $invoice_order;
		foreach (array('amount') as $p)
		{
			$order->{'_'.$p} = isset($order->$p) ? currency_format_amount($order->$p): 0;
		}
		foreach (array('complete', 'cancel') as $v)
		{
			$order->{'_url_'.$v} = admin_url('deposit_bank/'.$v.'/'.$order->id);
		}

		$data = compact('order');
		return view('tpl::deposit_bank/view', $data, true);
	}



}