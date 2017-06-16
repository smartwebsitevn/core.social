<?php namespace App\Accountant\Library;

use App\Invoice\Model\InvoiceOrderModel;

class ReasonByInvoiceOrder extends Reason
{
	/**
	 * Tao reason
	 *
	 * @param InvoiceOrderModel $invoice_order
	 * @return Reason
	 */
	public static function make(InvoiceOrderModel $invoice_order)
	{
		return new static([
			'invoice_order' => $invoice_order->getAttributes(),
		]);
	}

	/**
	 * Lay mo ta
	 *
	 * @return string
	 */
	public function desc()
	{
		return $this->invoiceOrderModel()->order_desc;
	}

	/**
	 * Lay url chi tiet
	 *
	 * @return string|null
	 */
	public function urlDetail()
	{
		return $this->invoiceOrderModel()->url('view');
	}

	/**
	 * Lay admin_url chi tiet
	 *
	 * @return string|null
	 */
	public function adminUrlDetail()
	{
		return $this->invoiceOrderModel()->adminUrl('view');
	}

	/**
	 * Lay doi tuong InvoiceOrderModel
	 *
	 * @return InvoiceOrderModel
	 */
	protected function invoiceOrderModel()
	{
		return new InvoiceOrderModel($this->getOption('invoice_order'));
	}
}