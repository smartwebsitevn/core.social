<?php namespace App\Invoice\Job;

use App\Invoice\InvoiceFactory as InvoiceFactory;
use App\Invoice\Library\InvoiceStatus;
use App\Invoice\Model\InvoiceModel as InvoiceModel;
use App\Invoice\Model\InvoiceOrderModel as InvoiceOrderModel;

class ActiveInvoice extends \Core\Base\Job
{
	/**
	 * Doi tuong InvoiceModel
	 *
	 * @var InvoiceModel
	 */
	protected $invoice;


	/**
	 * Khoi tao doi tuong
	 *
	 * @param InvoiceModel $invoice
	 */
	public function __construct(InvoiceModel $invoice)
	{
		$this->invoice = $invoice;
	}

	/**
	 * Thuc hien xu ly
	 */
	public function handle()
	{
		$this->updateInvoice();

		$this->activeInvoiceOrders();
	}

	/**
	 * Cap nhat thong tin invoice
	 */
	protected function updateInvoice()
	{
		$this->invoice->updateStatus(InvoiceStatus::PAID);

		InvoiceFactory::invoice()->logActivity('active', $this->invoice);
	}

	/**
	 * Kich hoat cac invoice_orders
	 */
	protected function activeInvoiceOrders()
	{
		foreach ($this->invoice->invoice_orders as $invoice_order)
		{
			$this->activeInvoiceOrder($invoice_order);
		}
	}

	/**
	 * Thuc hien kich hoat invoice_order
	 *
	 * @param InvoiceOrderModel $invoice_order
	 */
	protected function activeInvoiceOrder(InvoiceOrderModel $invoice_order)
	{
		$invoice_order->invoiceServiceInstance()->active($invoice_order);
	}

}