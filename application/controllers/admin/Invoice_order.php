<?php

use App\Invoice\InvoiceFactory as InvoiceFactory;
use App\Invoice\Library\OrderStatus;
use App\Invoice\Model\InvoiceOrderModel as InvoiceOrderModel;
use App\User\UserFactory;

class Invoice_order extends MY_Controller
{
	/**
	 * Khoi tao doi tuong
	 */
	public function __construct()
	{
		parent::__construct();

		t('lang')->load('modules/invoice/invoice');
	}

	/**
	 * List
	 */
	public function index_()
	{
		$filter = [];

		$user_key = t('input')->get('user_key');

		if ($user = UserFactory::user()->find($user_key))
		{
			$filter['user_id'] = $user->id;
		}

		$this->_list($list_args = [
			'filter' => true,
			'filter_fields' => [
				'id', 'invoice_id', 'user_id', 'user_ip', 'user_key', 'service_key','service_key_custom', 'key', 'amount', 'profit',
				'invoice_status', 'order_status', 'created', 'created_to',
			],
			'filter_value' => $filter,
			'input' => ['relation' => ['invoice.tran', 'user']],
			'order' => true,
			'order_fields' => ['id', 'service_key', 'order_status', 'amount', 'profit', 'created'],
			'actions' => ['view'],
			'actions_list' => [],
			'display' => false,
		]);

		foreach ($this->data['list'] as &$row)
		{
			$invoice = (array) $row->invoice;
			$invoice['trans'] = array_pull($invoice, 'tran');
			$invoice = (object) $invoice;
			$row->invoice =  mod('invoice')->add_info($invoice);
			//$row->invoice = (object) $invoice;

		}
		$this->data['list'] = InvoiceOrderModel::makeCollection($this->data['list']);

		$this->data['services'] = InvoiceFactory::invoiceServiceManager()->listInfo();
		$this->data['list_order_status'] = OrderStatus::lists();

		$this->data['sums'] = $this->_makeSumsList($list_args);

		//export
		$filter_input_export = $this->data['filter'];
		$filter_input_export['export'] = 1;
		$this->data['url_export'] = current_url() . '?' . url_build_query($filter_input_export);

		if ($this->input->get('export')) {
			$this->_export($this->data['list']);
		}
		else
			$this->_display('index');
	}
	public function index()
	{
		$filter['order_status'] ='completed';
		$this->data['order_type'] = 'completed';

		$this->_list_invoice($filter);
	}
	public function pending()
	{
		$filter['order_status'] ='pending';
		$filter['confirm'] =1;
		$this->data['order_type'] = 'pending';

		$this->_list_invoice($filter);
	}
	public function canceled()
	{
		$filter['order_status'] ='canceled';
		$filter['confirm'] =0;
		$this->data['order_type'] = 'cancel';

		$this->_list_invoice($filter);
	}
	public function draf()
	{
		$filter['order_status'] ='pending';
		$filter['confirm'] =0;
		$this->data['order_type'] = 'draf';

		$this->_list_invoice($filter);
	}

	/**
	 * List
	 */
	public function _list_invoice($filter)
	{

		$user_key = t('input')->get('user_key');

		if ($user = UserFactory::user()->find($user_key))
		{
			$filter['user_id'] = $user->id;
		}
		//model('invoice_order')->filter_get_list($filter);
		//pr_db($filter);
		$list_args = [
			'filter' => true,
			'filter_fields' => [
				'id', 'invoice_id', 'user_id', 'user_ip', 'user_key', 'service_key', 'key', 'amount', 'profit',
				'invoice_status', 'order_status', 'created', 'created_to',
			],
			'filter_value' => $filter,
			'input' => ['relation' => ['invoice.tran', 'user']],
			'order' => true,
			'order_fields' => ['id', 'service_key', 'order_status', 'amount', 'profit', 'created'],
			'actions' => ['view','del'],
			'actions_list' => [],
			'display' => false,
		];


		//export
		$filter_input = mod('invoice_order')->create_filter($list_args['filter_fields']);
		$filter = array_merge($filter, $filter_input);

		$filter_input_export = $filter;
		$filter_input_export['export'] = 1;
		$this->data['url_export'] = current_url() . '?' . url_build_query($filter_input_export);

		if ($this->input->get('export')) {

			// pr($filter);
			$list =model('invoice_order')->filter_get_list($filter);
			foreach ($list as &$row)
			{
				$row =  mod('invoice_order')->add_info($row);
			}
			$list = InvoiceOrderModel::makeCollection($list);

			$this->_export($list);
		}
		else{
			$this->_list($list_args);
			foreach ($this->data['list'] as &$row)
			{
				$invoice = (array) $row->invoice;

				$invoice['trans'] = array_pull($invoice, 'tran');

				$invoice = (object) $invoice;
				$row->invoice =  mod('invoice')->add_info($invoice);
			}

			$this->data['list'] = InvoiceOrderModel::makeCollection($this->data['list']);

			$this->data['services'] = InvoiceFactory::invoiceServiceManager()->listInfo();

			$this->data['list_order_status'] = OrderStatus::lists();

			$this->data['sums'] = $this->_makeSumsList($list_args);
			$this->_display('index');

		}
	}

	/**
	 * Tao sums
	 *
	 * @param array $list_args
	 * @return array
	 */
	protected function _makeSumsList(array $list_args)
	{
		$filter = $this->_mod()->create_filter($list_args['filter_fields']);

		$filter = array_merge($filter, $list_args['filter_value']);

		$sums = [];

		foreach (['amount_par', 'amount', 'profit'] as $param)
		{
			$sums[$param] = $this->_model()->filter_get_sum($param, $filter);

			$sums['format_'.$param] = currency_format_amount_default($sums[$param]);
		}

		return $sums;
	}

	/**
	 * View
	 */
	public function view()
	{
		$id = t('uri')->rsegment(3);

		$invoice_order = InvoiceOrderModel::find($id);

		if ( ! $invoice_order)
		{
			set_message(lang('notice_can_not_do'));

			$this->_redirect();
		}

		$invoice_order_view = $invoice_order->invoiceServiceInstance()->view($invoice_order);

		$this->data = array_merge($this->data, compact(
			'invoice_order', 'invoice_order_view'
		));

		$this->_display();
	}
	function _export($list)
	{
		$headers = array(
			'stt'   => lang('stt'),
			'invoice_id'		=> lang('id'),
			'invoice_type'		=> lang('type'),
			'invoice_desc'		=> lang('desc'),
			'customer_name'		=> lang('name'),
			'customer_phone'		=> lang('phone'),
			'customer_email'		=> lang('email'),
			'customer_address'		=> lang('address'),
			'invoice_amount'		=> lang('amount'),
			'invoice_status'		=> lang('order'),
			'invoice_payment'		=> lang('payment'),
			'invoice_created'	=> lang('created'),
		);
		$lists = array();
		$i = 1;
		foreach ($list as $row) {
			//$invoice = $row->invoice;
			/*$customer= array_filter($row->user
        ? [$row->user->name, $row->user->phone, $row->user->email, $row->user->address]
        : array_only($invoice->info_contact, ['name', 'phone','email','address']
        ));*/
			$invoice = model('invoice')->get_info($row->invoice_id);
			$invoice = mod('invoice')->add_info($invoice);


			$customer =$invoice->info_contact;
			$_list = array(
				'stt'   => $i,
				'invoice_id'		=> $row->id,
				'invoice_type'		=> $row->service_name,
				'invoice_desc'		=> $row->order_desc,
				'customer_name'		=>  isset($customer->name)?$customer->name:'-',
				'customer_phone'		=> isset($customer->phone)?$customer->phone:'-',
				'customer_email'		=> isset($customer->email)?$customer->email:'-',
				'customer_address'		=> isset($customer->address)?$customer->address:'-',
				'invoice_amount'		=> $row->{'format:amount'},
				'invoice_status'		=> $row->order_status_name,
				'invoice_payment'		=> $invoice->_payment_name,
				'invoice_created'   =>  $row->_created_full,
			);
			$lists[] = $_list;
			$i++;
		}

		$full_path = 'export/invoice.xlsx';

		write_file($full_path);
		lib('phpexcel')->export($headers, $lists, './'.$full_path);
		// Khai bao du lieu tra ve
		$result['complete'] = TRUE;
		$result['location'] = base_url($full_path);
		set_output('json', json_encode($result));

	}
}