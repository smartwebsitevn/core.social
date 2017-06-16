<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_group_model extends MY_Model
{
	var $table 	= 'admin_group';
	var $order 	= array('sort_order', 'asc');
	
	
	/**
	 * Lay danh sach cac permissions
	 */
	function get_permissions($id)
	{
		$info = $this->get_info($id, 'permissions');
		
		$permissions = (isset($info->permissions)) ? @unserialize($info->permissions) : array();
		$permissions = (!is_array($permissions)) ? array() : $permissions;
		
		return $permissions;
	}
	
}