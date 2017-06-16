<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Currency_model extends MY_Model {
	
	var $table = 'currency';
	var $order = array('sort_order', 'asc');
	
	
	/**
	 * Gan tien te mac dinh
	 */
	function set_default($id)
	{
		$default = $this->get_default('default');
		$default = ($default) ? $default->default : 0;
		
		$data = array();
		$data['default'] 	= $default + 1;
		$data['value'] 		= 1; // Tien te mac dinh luon co ti gia la 1
		$data['status'] 	= config('status_on', 'main');
		$data['show'] 		= config('verify_yes', 'main');
		$this->update($id, $data);
	}
	
	/**
	 * Lay tien te mac dinh
	 */
	function get_default($field = '')
	{
		static $default = null;
		if($default === null) {
			$input = array();
			//$input['select'] = $field;
			$input['where']['value'] = 1;
			$input['order'] = array('default', 'desc');
			$input['limit'] = array('0', '1');
			$default = $this->get_list($input);
			$default = (isset($default[0])) ? $default[0] : FALSE;
		}
		return $default;
	}
	
	/**
	 * Lay danh sach cac tien te duoc kich hoat
	 */
	function get_list_active($field = '')
	{
		$input = array();
		$input['select'] = $field;
		$input['where']['status'] = config('status_on', 'main');
		
		return $this->get_list($input);
	}
	
	/**
	 * Lay danh sach cac tien te duoc kich hoat va duoc hien thi
	 */
	function get_list_active_show($field = '')
	{
		$input = array();
		$input['select'] = $field;
		$input['where']['status'] 	= config('status_on', 'main');
		$input['where']['show'] 	= config('verify_yes', 'main');
		
		return $this->get_list($input);
	}
	
	/**
	 * Lay thong tin cua tien te duoc kich hoat va duoc hien thi
	 */
	function get_info_active_show($id, $field = '')
	{
		$where = array();
		$where['id'] 		= $id;
		$where['status'] 	= config('status_on', 'main');
		$where['show'] 		= config('verify_yes', 'main');
		
		return $this->get_info_rule($where, $field);
	}
	
}