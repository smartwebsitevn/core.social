<?php namespace App\Invoice\Model;

use App\Invoice\Model\InvoiceModel as InvoiceModel;

trait InvoiceRelationTrait
{
	/**
	 * Gan invoice
	 *
	 * @param InvoiceModel $invoice
	 */
	protected function setInvoiceAttribute(InvoiceModel $invoice)
	{
		$this->additional['invoice'] = $invoice;
	}

	/**
	 * Lay invoice
	 *
	 * @return InvoiceModel|null
	 */
	protected function getInvoiceAttribute()
	{
		if ( ! array_key_exists('invoice', $this->additional))
		{
			$invoice_id = $this->getAttribute('invoice_id');

			$this->additional['invoice'] = InvoiceModel::find($invoice_id);
		}

		return $this->additional['invoice'];
	}

}