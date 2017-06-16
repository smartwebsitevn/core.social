<?php namespace App\Invoice\Model;

use App\Invoice\Model\InvoiceOrderModel as InvoiceOrderModel;

trait InvoiceOrderRelationTrait
{
	/**
	 * Gan invoice_order
	 *
	 * @param InvoiceOrderModel $invoice_order
	 */
	protected function setInvoiceOrderAttribute(InvoiceOrderModel $invoice_order)
	{
		$this->additional['invoice_order'] = $invoice_order;
	}

	/**
	 * Lay invoice_order
	 *
	 * @return InvoiceOrderModel|null
	 */
	protected function getInvoiceOrderAttribute()
	{
		if ( ! array_key_exists('invoice_order', $this->additional))
		{
			$invoice_order_id = $this->getAttribute('invoice_order_id');

			$this->additional['invoice_order'] = InvoiceOrderModel::find($invoice_order_id);
		}

		return $this->additional['invoice_order'];
	}

}