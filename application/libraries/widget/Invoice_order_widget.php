<?php

use App\Invoice\Library\InvoiceStatus;
use App\Invoice\Model\InvoiceOrderModel;

class Invoice_order_widget extends MY_Widget
{
	/**
	 * Khoi tao doi tuong
	 */
	public function __construct()
	{
		t('lang')->load('modules/invoice/invoice');
	}

	/**
	 * Moi nhat
	 *
	 * @param array $opts
	 */
	public function newest(array $opts = [])
	{
		$total = array_get($opts, 'total', 20);

		$list = model('invoice_order')->filter_get_list([
			'invoice_status' => InvoiceStatus::PAID,
		], [
			'relation' => 'invoice.tran',
			'order'    => ['id', 'desc'],
			'limit'    => [0, $total],
		]);

		foreach ($list as &$row)
		{
			$invoice = (array) $row->invoice;

			$invoice['trans'] = array_pull($invoice, 'tran');

			$row->invoice = (object) $invoice;
		}

		$this->data['list'] = InvoiceOrderModel::makeCollection($list);
		$this->data['url_all'] = admin_url('invoice_order');

		$this->_display($this->_make_view(array_get($opts, 'view'), __FUNCTION__));
	}
}