<?php

use App\Invoice\InvoiceFactory as InvoiceFactory;
use App\Invoice\Handler\Request\InvoiceOrderView;
use App\Invoice\Library\InvoiceService;
use App\Invoice\Library\ServiceType;
use App\Invoice\Model\InvoiceOrderModel as InvoiceOrderModel;
use App\User\UserFactory;

class Invoice_order extends MY_Controller
{
    public $_template = 'dreamlife';
    
	/**
	 * Khoi tao doi tuong
	 */
	public function __construct()
	{
		parent::__construct();

		t('lang')->load('modules/invoice/invoice');

	}

	/**
	 * Danh sach
	 */
	public function index()
	{
		$this->_makeList([
			'title' => lang('title_invoice_order_list'),
			//'services' => $this->listInvoiceServices(),
			'services' => $this->listInvoiceServices([
				ServiceType::DEPOSIT,
				ServiceType::WITHDRAW,
				ServiceType::PRODUCTORDER,
				ServiceType::COMBOORDER,
				ServiceType::AFFILIATE,
				ServiceType::COMMISSON,
			]),
		]);
	}

	/**
	 * Danh sach invoice_order dang order
	 */
	public function orders()
	{
		$this->_makeList([
			'title' => lang('title_list_orders'),
			'services' => $this->listInvoiceServices(ServiceType::ORDER),
		]);
	}

	/**
	 * Danh sach invoice_order dang trans
	 */
	public function trans()
	{
		$this->_makeList([
			'title' => lang('title_list_trans'),
			'services' => $this->listInvoiceServices([
				ServiceType::DEPOSIT,
				ServiceType::WITHDRAW,
				ServiceType::TRANSFER_SEND,
				ServiceType::TRANSFER_RECEIVE,
			]),
		]);
	}
	/**
	 * Danh sach invoice_order dang trans
	 */
/*	public function affiliate()
	{
		$this->_makeList([
			'title' => lang('title_list_trans'),
			'services' => $this->listInvoiceServices([
				ServiceType::AFFILIATE,
			]),
		]);
	}*/

	/**
	 * Tao danh sach
	 */
	protected function _makeList(array $args)
	{
		if ( ! UserFactory::auth()->logged())
		{
			redirect_login_return();
		}

		$services 	= $args['services'];
		$title 		= $args['title'];
		$group_type = array_get($args, 'group_type', t('uri')->rsegment(2));
		$view 		= array_get($args, 'view', 'list');

		$user = UserFactory::auth()->user();

		$service_keys = array_pluck($services, 'key');

		$service_key = t('input')->get('service_key');

		$filter = [
			'order_status' => 'completed',
			'user_id'     => $user->id,
			'service_key' => in_array($service_key, $service_keys) ? $service_key : $service_keys,
		];

		$this->_list([
			'filter' => true,
			'filter_fields' => [
				'id', 'invoice_id', 'service_key', 'key', 'order_status', 'created', 'created_to',
			],
			'filter_value' => $filter,
			'input' => ['relation' => 'invoice.tran'],
			'actions' => ['view'],
			'display' => false,
		]);
		foreach ($this->data['list'] as &$row)
		{
			$invoice = (array) $row->invoice;

			$invoice['trans'] = array_pull($invoice, 'tran');

			$row->invoice = (object) $invoice;
		}

		$this->data['list'] = InvoiceOrderModel::makeCollection($this->data['list']);
		$this->data['services'] = $services;
		$this->data['title'] = $title;
		$this->data['group_type'] = $group_type;

		page_info('title', $title);

		$this->_display($view);
	}

	/**
	 * Lay danh sach invoice services
	 *
	 * @param null|array $types
	 * @return array
	 */
	protected function listInvoiceServices($types = null)
	{
		$list = InvoiceFactory::invoiceServiceManager()->listInfo();

		if (is_null($types)) return $list;

		return array_filter($list, function($row) use ($types)
		{
			$type = InvoiceFactory::invoiceService($row['key'])->type();

			return in_array($type, (array) $types);
		});
	}

	/**
	 * View
	 */
	public function view()
	{
		$input = array_merge(t('input')->get(), [
			'invoice_order_id' => t('uri')->rsegment(3),
		]);

		$response = (new InvoiceOrderView($input))->handle();
		$this->data = array_merge($this->data, $response);

		$this->_display();
	}

}