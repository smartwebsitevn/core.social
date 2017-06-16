<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Deposit_card_log extends MY_Controller
{
	/**
	 * Ham khoi dong
	 */
	public function __construct()
	{
		parent::__construct();

		$this->lang->load('admin/'.$this->_get_mod());
	}
	
	/**
	 * Remap method
	 */
	public function _remap($method, $params = array())
	{
		return $this->_remap_action($method, $params, array('view', 'del'));
	}
	
	/**
	 * List
	 */
	public function index()
	{
		$this->data['statuss'] = $this->_mod()->statuss();
		
		$this->data['providers'] = $this->_get_providers();
		$this->data['types'] =  model('card_type')->get_list();
		
		$list = array();
		$list['filter'] = true;
		$list['filter_fields'] = array('id', 'provider', 'type', 'code', 'serial', 'status', 'user_id', 'ip', 'created', 'created_to');
		$list['input'] = ['relation' => 'user'];
		$list['actions'] = array('view', 'active');
		$this->_list($list);
	}
	

	/**
	 * Lay danh sach providers
	 *
	 * @return array
	 */
	protected function _get_providers()
	{
	    $list = model('payment_card')->get_list_installed();
	    $list = model('payment_card')->get_list_info($list);
	
	    return $list;
	}
	
	
	/**
	 * View
	 */
	protected function _view($info)
	{
		$info = $this->_mod()->add_info($info);
		
		pr($info);
	}
	/**
	 * Xoa
	 */
	protected function _del($info)
	{
		$this->_model()->del($info->id);

		set_message(lang('notice_del_success'));
	}
}