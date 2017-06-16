<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Site_model extends MY_Model {
	
	var $table = 'site';
	var $key = 'key';
	var $order = array('key', 'asc');
	var $_types = array('text', 'list', 'html');
	
	
	/**
	 * Them moi
	 */
	function create($data)
	{
		// Kiem tra key nay da ton tai hay chua
		$id 	= $data[$this->key];
		$info 	= $this->get_info($id, $this->key);
		
		// Neu da ton tai thi cap nhat
		if ($info)
		{
			$this->update($id, $data);
		}
		// Neu chua ton tai thi them moi
		else 
		{
			parent::create($data);
		}
	}
	
	/**
	 * Luu thong tin
	 */
	function set($key, $value, $type)
	{
		$data = array();
		$data['key'] 	= $key;
		$data['value'] 	= $value;
		$data['type'] 	= (!isset($this->_types[$type])) ? '0' : $type;
		$this->create($data);
	}
	
	/**
	 * Lay thong tin
	 */
	function get($key)
	{
		$info = $this->get_info($key, 'value, type');
		if (!$info)
		{
			return FALSE;
		}
		
		return $this->_handle_value($info->value, $info->type);
	}
	
	/**
	 * Xu ly gia tri theo type
	 */
	function _handle_value($value, $type)
	{
		$type = (!isset($this->_types[$type])) ? '0' : $type;
		if ($this->_types[$type] == 'list')
		{
			return explode("\n", $value);
		}
		
		return $value;
	}
	
}
?>