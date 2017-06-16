<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH.'models/core/Core_info_key_model.php';

class Module_model extends Core_info_key_model {
	
	var $table 	= 'module';
	var $key 	= 'key';
	var $order 	= array('module.sort_order', 'asc');
	var $select = 'module.key, module.name, module.status, module.sort_order';
	var $fields_type_list = array('layout', 'setting');
	
	
	/**
	 * Lay setting
	 * @param string $key		Key cua module
	 * @param string $param		Bien muon lay gia tri, mac dinh la lay toan bo cac bien
	 */
	function get_setting($key, $param = '')
	{
		// Lay setting
		$info = $this->get($key, 'setting');
		$setting = ($info) ? $info->setting : FALSE;
		
		// Neu chi lay gia tri cua 1 bien
		if ($param != '')
		{
			return (isset($setting[$param])) ? $setting[$param] : FALSE;
		}
		
		return $setting;
	}
	
	/**
	 * Lay table name trong db
	 */
	function table_get_db_name($module_key, $table_key)
	{
		return "module:{$module_key}:{$table_key}";
	}
	
}