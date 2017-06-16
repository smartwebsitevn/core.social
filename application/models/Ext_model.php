<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ext_model extends MY_Model {
	
	var $table = 'ext';
	var $order = array('code', 'asc');
	
	/**
	 * Luu phan mo rong
	 */
	function set($type, $code)
	{
		// Kiem tra xem da ton tai hay chua
		$where = array();
		$where['type'] = $type;
		$where['code'] = $code;
		$info = $this->get_info_rule($where);
		
		// Neu chua ton tai thi them moi
		if (!$info)
		{
			$this->create($where);
		}
	}
	
	/**
	 * Lay cac phan mo rong cua type
	 */
	function get($type)
	{
		$input = array();
		$input['where']['type'] = $type;
		$list = $this->get_list($input);
		
		$codes = array();
		foreach ($list as $code)
		{
			$codes[] = $code->code;
		}
		
		return $codes;
	}
	
	/**
	 * Xoa phan mo rong
	 */
	function del($type, $code)
	{
		$where = array();
		$where['type'] = $type;
		$where['code'] = $code;
		
		$this->del_rule($where);
	}
	
}
?>