<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class File_model extends MY_Model {
	
	var $table = 'file';
	var $order 	= array('sort_order', 'asc');
	
	
/*
 * ------------------------------------------------------
 *  File of mod
 * ------------------------------------------------------
 */
	/**
	 * Lay danh sach cac file cua mod
	 */
	function get_list_of_mod($table, $table_id, $table_field = '', $field = '', $order = '', $size = 0)
	{
		$input = array();
		$input['where'] 	= $this->_get_where_of_mod($table, $table_id, $table_field);
		$input['select']	= $field;
		if ($order)
		{
			$input['order'] = $order;
		}
		if ($size)
		{
			$input['limit'] = array(0, $size);
		}
		
		return $this->get_list($input);
	}

	/**
	 * Lay thong tin file cua mod
	 */
	function get_info_of_mod($table, $table_id, $table_field = '', $field = '')
	{
		$where = $this->_get_where_of_mod($table, $table_id, $table_field);
		
		return $this->get_info_rule($where, $field);
	}
	
	/**
	 * Lay file_id cua mod
	 */
	function get_id_of_mod($table, $table_id, $table_field = '')
	{
		$where = $this->_get_where_of_mod($table, $table_id, $table_field);
		
		return $this->get_id($where);
	}
	
	/**
	 * Update table_id cua mod
	 */
	function update_table_id_of_mod($table, $fake_id, $table_id)
	{
		$where = array();
		$where['table'] 	= $table;
		$where['table_id'] 	= $fake_id;
		
		$data = array();
		$data['table_id'] 	= $table_id;
		
		$this->update_rule($where, $data);
	}
	
	/**
	 * Lay where cua mod
	 */
	function _get_where_of_mod($table, $table_id, $table_field)
	{
		$where = array();
		$where['table']		= $table;
		$where['table_id']	= $table_id;
		
		if ($table_field != '')
		{
			$where['table_field'] = $table_field;
		}
		
		return $where;
	}
	
	
/*
 * ------------------------------------------------------
 *  Other function
 * ------------------------------------------------------
 */
	/**
	 * Lay danh sach cac file tam thoi
	 */
	function get_list_temporary($field = '', $limit = 50)
	{
		$input = array();
		$input['select'] = $field;
		$input['where']['table_id <='] 	= '0';
		$input['where']['created <='] 	= now()-(2*60*60);
		if ($limit)
		{
			$input['limit'] = array(0, $limit);
		}
		
		return $this->get_list($input);
	}
	function totalSizeFile($table = '', $id = 0, $table_field = ''){
		$where = [];
		if($table)
			$where['where']['table'] = $table;
		if($id)
			$where['where']['table_id'] = $id;
		if($table_field)
			$where['where']['table_field'] = $table_field;
		if(!$where)
			return false;
		$where['select'] = 'sum(size) as total, count(*) as total_file';
		$query = $this->select($where);
		if(isset($query[0]))
			return ['sizes' => $query[0]->total ? $query[0]->total : 0, 'files' => $query[0]->total_file ? $query[0]->total_file: 0];
		return ['sizes' => 0, 'files' => 0];
	}
}