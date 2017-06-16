<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
use App\Accountant\AccountantFactory as AccountantFactory;
use App\Accountant\ChangeBalanceReason\Deposit as DepositReason;
class Plan_order_mod extends MY_Mod
{

	/**
	 * Tao order
	 *
	 * @param array $input
	 * 	array 		'cart'			Thong tin cart (la ket qua tra ve cua fun mod('topup_offline')->get_cart())
	 * 	string		'tran_status' 	= 'pending'
	 * 	string		'tran_payment' 	= ''
	 * 	string		'order_status' 	= 'pending'
	 * 	int			'user_id' 		= 0
	 * 	int			'user_balance' 	= 0
	 * 	string		'user_ip' 		= ''
	 * 	array		'contact' 		= array()
	 * @param array $output
	 * @return int
	 */
	public function create(array $input, &$output = array())
	{
		// Xu ly input
		$plan	= array_get($input,'plan');
		$amount	= array_get($input, 'amount');
		$user		= array_get($input, 'user');
		$purse		= array_get($input, 'purse');


		$order_options = array();
		//tao Invoice
		$invoice = $this->createInvoice($amount, $user);

		//tao Invoice_order
		$invoice_order = $this->createInvoiceOrder($invoice, $order_options);


		//them vao bang plan_order
		// Cap nhat vao data
		$data = array();
		$data['plan_id']          = $plan->id;
		$data['user_id']          = $user->id;
		$data['invoice_id']       = $invoice->id;
		$data['invoice_order_id'] = $invoice_order->id;
		$data['status']         = 'completed';
		$data['created']		= now();
		$plan_order= model('plan_order')->create($data);

		$output = compact('invoice', 'invoice_order','plan_order');


	}

	/**
	 * Tao invoice
	 *
	 * @return InvoiceModel
	 */
	protected function createInvoice($amount, $user)
	{
		$options = new \App\Invoice\Library\CreateInvoiceOptions([
			'amount'  => $amount, // tinh theo tien te mac dinh
			'user_id' => $user->id, // Ma thanh vien
			'status' => 'paid', // unpaid or paid
		]);

		$invoice = \App\Invoice\InvoiceFactory::invoice()->create($options);

		return $invoice;
	}

	/**
	 * Tao invoice order
	 *
	 * @param InvoiceModel $invoice
	 * @return InvoiceOrderModel
	 */
	protected function createInvoiceOrder($invoice, $order_options)
	{
		$options = new \App\Invoice\Library\CreateInvoiceOrderOptions([
			'invoice'     => $invoice,
			'service_key'   => 'PlanOrder',
			'amount'        => $invoice->amount,
			'order_status'  => 'completed',
			'order_options' => $order_options, // Khai bao thong tin order (dung de tao mo ta va tim kiem)
		]);

		$invoice_order = \App\Invoice\InvoiceFactory::invoiceOrder()->create($options);
		return $invoice_order;
	}

}