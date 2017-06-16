<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sms_gateway_log extends MY_Controller
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
		return $this->_remap_action($method, $params, array('view'));
	}
	
	/**
	 * List
	 */
	public function index()
	{
		$this->data['statuss'] = $this->_mod()->statuss();
		
		$list = array();
		$list['filter'] = true;
		$list['filter_fields'] = array('id', 'port', 'message', 'status','created', 'created_to');
		$this->_list($list);
	}
	


	/**
	 * View
	 */
	protected function _view($info)
	{
	    $info = $this->_mod()->add_info($info);
	    
	}
}

