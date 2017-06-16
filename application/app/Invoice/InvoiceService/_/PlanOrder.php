<?php namespace App\Invoice\InvoiceService;

use App\Invoice\Library\InvoiceService;
use App\Invoice\Library\ServiceType;
use App\Invoice\Model\InvoiceOrderModel as InvoiceOrderModel;
use App\Payment\Model\PaymentModel;
use App\Payment\PaymentFactory as PaymentFactory;
use App\PlanOrder\Model\PlanOrderModel;

class PlanOrder extends InvoiceService
{
	/**
	 * Khoi tao doi tuong
	 */
	public function __construct()
	{
		//t('lang')->load('modules/renewtv/renewtv');
		t('lang')->load('site/plan_order');
	}

	/**
	 * Lay loai dich vu (lay theo ServiceType::***)
	 *
	 * @return string
	 */
	public function type()
	{
		return 'plan_order';//ServiceType::RENEWMOVIE;
	}


	/**
	 * Lay mo ta cua order
	 *
	 * @param InvoiceOrderModel $invoice_order
	 * @return null|string|array
	 */
	public function getOrderDesc(InvoiceOrderModel $invoice_order)
	{
		//$plan_order = PlanOrderModel::findByInvoiceOrder($invoice_order->id);
		//pr($plan_order);
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
			'name' => 'Gia hạn xem phim',
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
		return null;
		/*$plan_order = PlanOrderModel::findByInvoiceOrder($invoice_order->id);
		if ( ! $plan_order) return null;
		$plan_order->invoice_order = $invoice_order;
		$data = compact('plan_order');
		return view('tpl::plan_order/view', $data, true);*/
	}

	

}