<?php namespace App\Invoice\Library;

use App\Invoice\Model\InvoiceOrderModel as InvoiceOrderModel;
use App\Invoice\Library\InvoiceService\Order as ServiceOrder;

abstract class InvoiceService
{
	/**
	 * Key cua driver
	 *
	 * @var string
	 */
	protected $key;


	/**
	 * Lay loai dich vu (lay theo ServiceType::***)
	 *
	 * @return string
	 */
	abstract public function type();

	/**
	 * Lay thong tin
	 *
	 * @return array
	 */
	abstract public function info();

	/**
	 * Kich hoat invoice
	 *
	 * @param InvoiceOrderModel $invoice_order
	 */
	public function active(InvoiceOrderModel $invoice_order){}

	/**
	 * View invoice
	 *
	 * @param InvoiceOrderModel $invoice_order
	 * @return string|null
	 */
	public function view(InvoiceOrderModel $invoice_order)
	{
		return null;
	}

	/**
	 * Lay mo ta cua order
	 *
	 * @param InvoiceOrderModel $invoice_order
	 * @return null|string|array
	 */
	public function getOrderDesc(InvoiceOrderModel $invoice_order)
	{
		return null;
	}

	/**
	 * Lay thong tin orders
	 *
	 * @param array $invoice_orders
	 * @return array
	 */
	public function findOrders(array $invoice_orders)
	{
		return [];
	}

	/**
	 * Lay thong tin cua 1 order
	 *
	 * @param InvoiceOrderModel $invoice_order
	 * @return ServiceOrder|null
	 */
	public function findOrder(InvoiceOrderModel $invoice_order)
	{
		return head($this->findOrders([$invoice_order])) ?: null;
	}

	/**
	 * Lay ten trang thai cua order
	 *
	 * @param string $status
	 * @return string
	 */
	public function getOrderStatusName($status)
	{
		$lang_key = 'order_status_'.$status;

		$lang = lang($lang_key);

		return $lang == $lang_key ? $status : $lang;
	}

	/**
	 * Lay key
	 *
	 * @return string
	 */
	public function key()
	{
		return $this->key ?: class_basename($this);
	}

}