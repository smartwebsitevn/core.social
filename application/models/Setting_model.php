<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setting_model extends MY_Model {
	
	var $table 	= 'setting';
	var $key 	= 'key';

	//public $translate_auto = TRUE;
	public $translate_fields = array('name','meta_key','meta_desc');

	/**
	 * Gan gia tri theo key
	 */
	function set($key, $value)
	{
		// Tao bien luu vao data
		$data = array();
		$data['value'] = serialize($value);
		
		// Neu chua ton tai key thi them moi
		$info = $this->get_info($key, 'key');
		if ( ! $info)
		{
			$data['key'] = $key;
			$this->create($data);
		}
		// Neu da ton tai key thi update
		else 
		{
			$this->update($key, $data);
		}
	}
	
	/**
	 * Gan gia tri theo group
	 */
	function set_group($group, array $data)
	{
		foreach ($data as $key => $value)
		{
			$key = $group.'-'.$key;
			$this->set($key, $value);
		}
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Lay gia tri theo key
	 */
	function get($key)
	{
	 	$info = $this->get_info($key, 'value');
		if (isset($info->value))
		{
			return @unserialize($info->value);
		}
		
		return FALSE;
	}
	
	/**
	 * Lay gia tri theo group
	 */
	function get_group($group)
	{
	 	$this->db->like('key', $group.'-', 'after'); 
		$query 	= $this->db->get('setting');
		$result = $query->result();
		
		$values = array();
		foreach ($result as $row)
		{
			$key 	= preg_replace('#^'.$group.'-(.*?)$#i', '${1}', $row->key);
			$value 	= @unserialize($row->value);
			
			$values[$key] = $value;
		}
		
		return $values;
	}
	
	/**
	 * Lay gia tri theo group cua post
	 */
	function get_group_post($group, $post)
	{
		$data = $this->get_group($group);
		
		$result = array();
		foreach ($data as $p => $v)
		{
			$match = '';
			if (preg_match('#^(.+)-'.$post.'$#i', $p, $match))
			{
				$result[$match[1]] = $v;
			}
		}
		
		return $result;
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Xoa setting theo group
	 */
	function del_group($group)
	{
		$this->db->like('key', $group.'-', 'after'); 
		$this->db->delete('setting');
	}
	
}