<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Topup_offline extends MY_Controller
{
	/**
	 * Khoi tao doi tuong
	 */
	public function __construct()
	{
		parent::__construct();
		
		$this->load->helper('tran');
	}
	
	
	/**
	 * Remap method
	 */
	function _remap($method)
	{
	    if (in_array($method, array(
	        'active', 'del', 'cancel'
	    )))
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
	 * Thuc hien tuy chinh
	 */
	function _action($action)
	{
	    // Lay input
	    $ids = $this->uri->rsegment(3);
	    $ids = (!$ids) ? $this->input->post('id') : $ids;
	    $ids = (!is_array($ids)) ? array($ids) : $ids;
	
	    // Thuc hien action
	    foreach ($ids as $id)
	    {
	        // Xu ly id
	        $id = (!is_numeric($id)) ? 0 : $id;
	
	        // Kiem tra id
	        $info = model('topup_offline')->get_info($id);
	        if (!$info) continue;
	         
	        // Chuyen den ham duoc yeu cau
	        $this->{'_'.$action}($info);
	    }
	}
	
	/**
	 * Chap nhan thong tin xac thuc
	 */
	function _active($info)
	{
	    // Gan trang thai xac thuc
	    $data = array();
	    $data['status'] = mod('order')->status('completed');
	
	    // Cap nhat du lieu vao data
	    model('topup_offline')->update($info->id, $data);
	    
	    $data = array();
	    $data['order_status'] = 'completed';
	    model('invoice_order')->update($info->invoice_order_id, $data);
	    
	    // Gui thong bao
	    set_message(lang('notice_update_success'));
	    return TRUE;
	}
	
	/**
	 * Chap nhan thong tin xac thuc
	 */
	function _cancel($info)
	{
	    // Gan trang thai xac thuc
	    $data = array();
	    $data['status'] = mod('order')->status('canceled');
	
	    // Cap nhat du lieu vao data
	    model('topup_offline')->update($info->id, $data);
	    
	    $data = array();
	    $data['order_status'] = 'canceled';
	    model('invoice_order')->update($info->invoice_order_id, $data);
	     
	    // Gui thong bao
	    set_message(lang('notice_update_success'));
	    return TRUE;
	}
	
	/**
	 * Xoa
	 */
	protected function _del($info)
	{
		tran_action($info->id, 'del');
		
		set_message(lang('notice_del_success'));
	}
	
	/**
	 * Hoan tien
	 */
	protected function _refund($info)
	{
		tran_action($info->id, 'refund');
		
		set_message(lang('notice_update_success'));
	}
	
}