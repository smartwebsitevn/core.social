<?php namespace App\Invoice\Service;

use App\Invoice\InvoiceFactory;
use App\Invoice\Job\ActiveInvoice;
use App\Invoice\Job\CreateInvoice;
use App\Invoice\Library\CreateInvoiceOptions;
use App\Invoice\Library\CreateInvoiceOrderOptions;
use App\Invoice\Model\InvoiceModel as InvoiceModel;
use App\Invoice\Model\InvoiceOrderModel as InvoiceOrderModel;
use App\LogActivity\LogActivityFactory as LogActivityFactory;
use App\LogActivity\Library\ActivityOwner as ActivityOwner;
use App\LogActivity\Model\LogActivityModel as LogActivityModel;

class InvoiceService
{
	/**
	 * Tao invoice
	 *
	 * @param CreateInvoiceOptions $options
	 * @return InvoiceModel
	 */
	public function create(CreateInvoiceOptions $options)
	{
		return (new CreateInvoice($options))->handle();
	}

	/**
	 * Kich hoat invoice
	 *
	 * @param InvoiceModel $invoice
	 */
	public function active(InvoiceModel $invoice)
	{
		(new ActiveInvoice($invoice))->handle();
	}

	/**
	 * Log activity
	 *
	 * @param string        $action
	 * @param InvoiceModel  $invoice
	 * @param ActivityOwner $owner
	 * @param array         $context
	 * @return LogActivityModel
	 */
	public function logActivity($action, InvoiceModel $invoice, ActivityOwner $owner = null, array $context = [])
	{
		$logger = LogActivityFactory::logger('Invoice');

		$context['invoice'] = $invoice->getAttributes();

		return $logger->log($action, $invoice->id, $owner, $context);
	}

	/**
	 * Lay invoice amount tuong ung voi currency
	 *
	 * @param InvoiceModel $invoice
	 * @param int          $currency_id
	 * @return float
	 */
	public function getAmountCurrency(InvoiceModel $invoice, $currency_id)
	{
		$amount = array_get($invoice->amounts_currency, (int) $currency_id);

		if ( ! $amount)
		{
			$amount = currency_convert_amount($invoice->amount, $currency_id);
		}

		return $amount;
	}

	/**
	 * Kiem tra invoice co ton tai invoice_order thuoc 1 service_type nao do hay khong
	 *
	 * @param InvoiceModel $invoice
	 * @param string       $service_type
	 * @return bool
	 */
	public function hasInvoiceOrderOfServiceType(InvoiceModel $invoice, $service_type)
	{
		$service_types = $invoice->invoice_orders->lists('service_type');

		return in_array($service_type, $service_types, true);
	}

}