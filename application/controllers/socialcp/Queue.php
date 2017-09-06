<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Queue extends MY_Controller
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
		$this->_list([
			'filter' => true,
			'filter_fields' => ['id', 'key', 'status', 'created', 'created_to'],
			'input' => ['select' => 'queue.*'],
			'order' => true,
			'order_fields' => ['key', 'status', 'created', 'handled'],
			'actions' => ['view'],
		]);
	}
	
	/**
	 * View
	 */
	protected function _view($info)
	{
		$info = $this->_mod()->add_info($info);
		
		pr($info);
	}
	
}