<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Translate_system_model extends MY_Model {
	
	var $table 	= 'translate_system';
	
	
	/**
	 * Filter handle
	 */
	function _filter_get_where(array $filter)
	{
		$where = parent::_filter_get_where($filter);
		
		foreach (array('lang_id', 'folder', 'file', 'key', 'value') as $p)
		{
			$f = (in_array($p, array())) ? $p.'_id' : $p;
			$f = 'translate_system.'.$f;
			$m = (in_array($p, array())) ? 'range' : '';
			$this->_filter_set_where($filter, $p, $f, $where, $m);
		}
		
		return $where;
	}
	
	/**
	 * Luu cac ban dich cua row
	 */
	function set($lang_id,$folder, $file, $key,$value)
	{

				// Lay ban dich tuong ung
				$where = array();
				$where['folder'] 		= $folder;
				$where['file'] 		= $file;
				$where['key'] 	= $key;
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
	
	/**
	 * Lay cac ban dich cua row
	 */
	function get($lang_id ,$folder, $file, $key = NULL)
	{
		// Tao filter
		$filter = array();
        $filter['folder'] = $folder;
		$filter['file']	= $file;
        $filter['lang_id'] = $lang_id;
		if ($key !== NULL)
		{
			$filter['key'] = $key;
		}
       // pr($filter,false);
        $list = $this->filter_get_list($filter);
       	// Xu ly list
		$result = array();
		foreach ($list as $row)
		{
			$result[$row->key] = handle_content($row->value, 'output');
		}

		return $result;
	}
	
	/**
	 * Lay cac ban dich cua table
	 */
	function get_folder($folder,$file,$key = NULL, $lang_id=NULL)
	{

    	// Tao filter
		$filter = array();
        $filter['folder'] = $folder;
		$filter['file']	= $file;

		if ($key !== NULL)
		{
			$filter['key'] = $key;
		}

		if ($lang_id !== NULL)
		{
			$filter['lang_id'] = $lang_id;
		}
		// Lay danh sach trong data

        //pr($filter);
		$list = $this->filter_get_list($filter);
		
		// Xu ly list
		$result = array();
		foreach ($list as $row)
		{
			$result[$row->file][$row->key][$row->lang_id] = handle_content($row->value, 'output');
		}
		
		return $result;
	}
		/**
	 * Tim kiem du lieu
	 */

	function search_value($value,$lang_id)
	{
			
	 	$where = array();
	 	$where[$this->table.'.lang_id'] = $lang_id;
	    $this->db->where($where);
		$this->db->like($this->table.'.value', $value);
		$list = $this->db->get($this->table)->result();
		//pr($this->db->last_query());
		return $list;
	}
	
}