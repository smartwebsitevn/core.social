<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Translate_model extends MY_Model {
	
	var $table 	= 'translate';
	
	
	/**
	 * Filter handle
	 */
	function _filter_get_where(array $filter)
	{
		$where = parent::_filter_get_where($filter);
		
		foreach (array('id', 'table', 'table_id', 'table_field', 'lang_id') as $p)
		{
			$f = (in_array($p, array())) ? $p.'_id' : $p;
			$f = 'translate.'.$f;
			$m = (in_array($p, array())) ? 'range' : '';
			$this->_filter_set_where($filter, $p, $f, $where, $m);
		}
		
		return $where;
	}
	
	/**
	 * Luu cac ban dich cua row
	 */
	function set($table, $table_id, array $data)
	{
		foreach ($data as $table_field => $lang_value)
		{
			foreach ($lang_value as $lang_id => $value)
			{
				// Lay ban dich tuong ung
				$where = array();
				$where['table'] 		= $table;
				$where['table_id'] 		= $table_id;
				$where['table_field'] 	= $table_field;
				$where['lang_id'] 		= $lang_id;
				$id = $this->get_id($where);
				
				// Neu ton tai gia tri
				$value = handle_content($value, 'input');
				if ($value != '')
				{
					// Neu da ton tai ban dich thi cap nhat
					if ($id)
					{
						$_data = array();
						$_data['value'] = $value;
						$this->update($id, $_data);
					}
					// Neu chua ton tai ban dich thi them moi
					else 
					{
						$_data = $where;
						$_data['value'] = $value;
						$this->create($_data);
					}
				}
				
				// Neu khong ton tai gia tri nhung ton tai ban dich thi xoa ban dich
				elseif ($id)
				{
					$this->del($id);
				}
			}
		}
	}
	
	/**
	 * Lay cac ban dich cua row
	 */
	function get($table, $table_id, $table_field = NULL, $lang_id = NULL)
	{
		// Tao filter
		$filter = array();
		$filter['table_id']	= $table_id;
		
		if ($table_field !== NULL)
		{
			$filter['table_field'] = $table_field;
		}
		
		if ($lang_id !== NULL)
		{
			$filter['lang_id'] = $lang_id;
		}
		
		// Lay gia tri
		$info = $this->get_table($table, $filter);
		$info = (isset($info[$table_id])) ? $info[$table_id] : FALSE;
		
		return $info;
	}
	
	/**
	 * Lay cac ban dich cua table
	 */
	function get_table($table, array $filter = array())
	{
		// Lay danh sach trong data
		$filter['table'] = $table;
		$list = $this->filter_get_list($filter);
		
		// Xu ly list
		$result = array();
		foreach ($list as $row)
		{
			$result[$row->table_id][$row->table_field][$row->lang_id] = handle_content($row->value, 'output');
		}
		
		return $result;
	}
	
}