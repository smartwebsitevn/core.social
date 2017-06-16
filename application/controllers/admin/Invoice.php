<?php
use App\Invoice\Model\InvoiceModel as InvoiceModel;
use App\Invoice\Handler\Request\InvoicePayment;
use App\Invoice\Handler\Request\InvoiceView;
use App\User\UserFactory;

class Invoice extends MY_Controller
{

	/**
	 * Khoi tao doi tuong
	 */
	public function __construct()
	{
		parent::__construct();

		t('lang')->load('admin/invoice');
	}

	/**
	 * Danh sach
	 */
	public function index()
	{
		$this->_makeList([
			'title' => lang('title_invoice_list')
		]);
	}

	/**
	 * Payment
	 */
	public function payment()
	{
		$input = $this->_makeRequestActionInput();

		$response = (new InvoicePayment($input))->handle();

		$this->data = array_merge($this->data, $response);

		$this->_display();
	}

	/**
	 * View
	 */
	public function view()
	{
		$input = $this->_makeRequestActionInput();

		$response = (new InvoiceView($input))->handle();

		$this->data = array_merge($this->data, $response);

		$this->_display();
	}

	/**
	 * Tao request input khi thuc hien action
	 *
	 * @return array
	 */
	protected function _makeRequestActionInput()
	{
		return array_merge(t('input')->get(), [
			'invoice_id' => t('uri')->rsegment(3),
		]);
	}

	/**
	 * Tao danh sach
	 */
	protected function _makeList(array $args)
	{

		$title 		= $args['title'];
		$view 		= array_get($args, 'view', 'index');


		// Tao filter
		$filter_input 	= array();
		$filter = mod('invoice')->create_filter( array('id', 'user_id', 'status', 'created', 'created_to'), $filter_input);
		$this->data['filter'] = $filter_input;

		// Lay tong so
		$total = model('invoice')->filter_get_total($filter);
		$page_size = config('list_limit', 'main');
		$limit = $this->input->get('per_page');
		$limit = max(0, min($limit, get_limit_page_last($total, $page_size)));
		
		// Lay danh sach
		$input = array();
		$input['limit'] = array($limit, $page_size);
		$input['relation'] = 'invoice.tran';
		$this->data['list'] = model('invoice')->filter_get_list($filter, $input);

		foreach ($this->data['list'] as &$row)
		{
			$row = mod('invoice')->add_info($row);
		}

		$this->data['list'] = InvoiceModel::makeCollection($this->data['list']);
		$this->data['title'] = $title;

		//$this->data['services'] = InvoiceFactory::invoiceServiceManager()->listInfo();
		//$this->data['status'] = OrderStatus::lists();
		page_info('title', $title);

		$this->_display($view);
	}

}