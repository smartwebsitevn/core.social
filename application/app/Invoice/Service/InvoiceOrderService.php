<?php namespace App\Invoice\Service;

use App\Invoice\Library\CreateInvoiceOrderOptions;
use App\Invoice\Model\InvoiceOrderModel;

class InvoiceOrderService
{
	/**
	 * Tao InvoiceOrder
	 *
	 * @param CreateInvoiceOrderOptions $options
	 * @return InvoiceOrderModel
	 */
	public function create(CreateInvoiceOrderOptions $options)
	{
		$data = array_merge($options->except(['invoice']), [
			'invoice_id'     => $options->invoice->id,
			'invoice_status' => $options->invoice->status,
			'user_id'        => $options->invoice->user_id,
			'user_ip'        => $options->invoice->user_ip,
			'keywords'       => $options->keywords,
			'secret_key'     => random_string('unique'),
		]);

		return InvoiceOrderModel::create($data);
	}

}