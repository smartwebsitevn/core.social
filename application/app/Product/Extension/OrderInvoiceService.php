<?php namespace App\Product\Extension;

use App\Invoice\Library\InvoiceService as BaseInvoiceService;
use App\Invoice\Library\ServiceType;
use App\Invoice\Model\InvoiceOrderModel as InvoiceOrderModel;
use App\Product\Job\ViewOrder\Factory as ViewOrder;
use App\Product\Library\ProductType;
use App\Product\Model\OrderModel as OrderModel;
use App\Product\ProductFactory as ProductFactory;

abstract class OrderInvoiceService extends BaseInvoiceService
{
	/**
	 * Khoi tao doi tuong
	 */
	public function __construct()
	{
		t('lang')->load('modules/product/common');
	}

	/**
	 * Lay loai dich vu (lay theo ServiceType::***)
	 *
	 * @return string
	 */
	public function type()
	{
		return ServiceType::ORDER;
	}

	/**
	 * Lay mo ta cua order
	 *
	 * @param InvoiceOrderModel $invoice_order
	 * @return null|string|array
	 */
	public function getOrderDesc(InvoiceOrderModel $invoice_order)
	{
		$type = array_get($invoice_order->order_options, 'type');

		$params = $this->makeOrderDescParams($invoice_order);

		return lang('order_desc_'.$type, $params);
	}

	/**
	 * Tao order desc params
	 *
	 * @param InvoiceOrderModel $invoice_order
	 * @return array
	 */
	protected function makeOrderDescParams(InvoiceOrderModel $invoice_order)
	{
		$option = function($key) use ($invoice_order)
		{
			return array_get($invoice_order->order_options, $key);
		};

		$type = $option('type');

		switch ($type)
		{
			case ProductType::CARD:
			case ProductType::SHIP:
			{
				return [
					'product'  => $option('product_name'),
					'quantity' => $option('quantity'),
				];
			}

			case ProductType::TOPUP_MOBILE:
			{
				return [
					'product' => $option('product_name'),
					'phone'   => $option('account'),
				];
			}

			case ProductType::TOPUP_MOBILE_POST:
			{
				return [
					'product' => $option('product_name'),
					'amount'   => number_format($option('quantity')),
					'phone'    => $option('account'),
				];
			}

			case ProductType::TOPUP_GAME:
			{
				return [
					'product' => $option('product_name'),
					'account' => $option('account'),
				];
			}
		}

		return [];
	}

	/**
	 * Kich hoat invoice
	 *
	 * @param InvoiceOrderModel $invoice_order
	 */
	public function active(InvoiceOrderModel $invoice_order)
	{
		$order = OrderModel::findByInvoiceOrder($invoice_order->id);

		if ( ! $order || ! $order->can('active')) return;

		try
		{
			ProductFactory::order()->active($order);
		}
		catch (\Exception $e){}
	}

	/**
	 * View invoice
	 *
	 * @param InvoiceOrderModel $invoice_order
	 * @return string|null
	 */
	public function view(InvoiceOrderModel $invoice_order)
	{
		$order = OrderModel::findByInvoiceOrder($invoice_order->id);

		if ( ! $order) return null;

		t('lang')->load('modules/product/order');

		$order->invoice_order = $invoice_order;

		$data = (new ViewOrder($order))->handle();

		$view = in_array($order->type, [
			ProductType::TOPUP_MOBILE,
			ProductType::TOPUP_MOBILE_POST,
			ProductType::TOPUP_GAME,
		]) ? 'topup' : $order->type;

		return view('tpl::product_order/view/'.$view, $data, true);
	}
}