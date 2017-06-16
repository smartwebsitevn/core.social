<?php namespace App\Accountant\ChangeStockReason;

use App\Accountant\Library\ReasonByInvoiceOrder;

class Order extends ReasonByInvoiceOrder
{
	/**
	 * Lay mo ta
	 *
	 * @return string
	 */
	public function desc()
	{
		$invoice_order_id = $this->getOption('invoice_order.id');

		return 'Xuất kho cho đơn hàng #'.$invoice_order_id;
	}

}