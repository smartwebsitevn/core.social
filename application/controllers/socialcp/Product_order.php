<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
use App\Invoice\InvoiceFactory as InvoiceFactory;
use App\Invoice\Library\OrderStatus;
use App\Invoice\Model\InvoiceModel as InvoiceModel;
use App\User\UserFactory;

class Product_order extends MY_Controller {
	public $actions = array( 'view'/*, 'del'*/ );
	public $filter = array('id', 'from_date', 'to_date', 'status');
	
	/**
	 * Ham khoi dong
	 */
	function __construct()
	{
		parent::__construct();
		
		// Tai cac file thanh phan
		$this->load->helper('extend');
		$this->load->helper('site');
		t('lang')->load('admin/invoice');
	}
	function _remap($method)
	{
		if (in_array($method, array( /*'del',*/'active','completed' ,'cancel')))
		{
			$this->_action($method);
		}
		elseif (method_exists($this, $method))
		{
			$this->{$method}();
		}
		else
		{
			show_404('', FALSE);
		}
	}
	/**
	 * Home
	 */
	public function index()
	{
		redirect_admin('invoice_order');
	}


	/**
	 * Thuc hien tuy chinh
	 */
	function _action($action)
	{
		// Lay input
		$ids = $this->uri->rsegment(3);
		$ids = ( ! $ids) ? $this->input->post('id') : $ids;
		$ids = ( ! is_array($ids)) ? array($ids) : $ids;
		
		// Thuc hien action
		foreach ($ids as $id)
		{
			// Xu ly id
			$id = ( ! is_numeric($id)) ? 0 : $id;
			
			// Kiem tra id
			$info = App\Invoice\Model\InvoiceOrderModel::findWhere(compact('id'));;
			if ( ! $info) continue;
			
			// Kiem tra co the thuc hien hanh dong nay khong
			if ( ! $this->_can_do($info, $action)) continue;
			
			// Chuyen den ham duoc yeu cau
			$this->{'_'.$action}($info);
		}
	}
	/**
	 * Kich hoat
	 */
	public function _active($info)
	{
		/*switch ($info->service_key) {
			case 'CourseOrder':
				$invoice_order_com = new \App\Invoice\InvoiceService\CourseOrder();
				break;
			case 'ComboOrder':
				$invoice_order_com = new \App\Invoice\InvoiceService\ComboOrder();
				break;
		}*/
		//pr($info);
		$invoice_order_com = new \App\Invoice\InvoiceService\ProductOrder();
		model("invoice")->update($info->invoice_id,['status' => "paid"]);
		model("invoice_order")->update($info->id,['invoice_status' => "paid",'order_status' => "completed"]);
		$invoice_order_com->active($info);

	}
	/**
	 * Kich hoat
	 */
	public function _completed($info)
	{
		model("invoice_order")->update($info->id,['order_status' => "completed"]);

	}
	/**
	 * Huy bo
	 */
	public function _cancel($info)
	{
		model("invoice_order")->update($info->id,['order_status' => "canceled"]);
	}

	public function _can_do($info,$action)
	{
		switch ($action)
		{
			case 'active':
			case 'completed':
			case 'cancel':
			{
				return in_array($info->order_status, [OrderStatus::PENDING, OrderStatus::PROCESSING]);
			}
		}

		return false;
	}
}