<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class download_model extends MY_Model {
	
	public $table 	= 'download';
	//public $select = 'id, name, summary, image_name, created, count_view, feature';
	public $order	= array( array('order', 'asc'),array('id', 'desc'));
	public $translate = TRUE;
	public $_options = array('feature');
	
	
/*
 * ------------------------------------------------------
 *  Main handle
 * ------------------------------------------------------
 */
	/**
	 * Filter handle
	 */
	function _filter_get_where(array $filter)
	{
		$where = parent::_filter_get_where($filter);
		
		foreach (array('id', 'created', 'category') as $p)
		{
			$f = (in_array($p, array('category'))) ? 'cat_id' : $p;
			$f = $this->table.'.'.$f;
			$m = (in_array($p, array('created'))) ? 'range' : '';
			$this->_filter_set_where($filter, $p, $f, $where, $m);
		}
		
		if (isset($filter['name']))
		{
			$this->search($this->table, 'name', $filter['name']);
		}
		if (isset($filter['status']))
		{
			$where[$this->table.'.'.'status'] = $filter['status'];
		}
		if (isset($filter['lang_id']))
		{
			$where[$this->table.'.'.'lang_id'] = $filter['lang_id'];
		}
		foreach ($this->_options as $p)
		{
			if ( ! isset($filter[$p])) continue;
			
			if ($filter[$p])
			{
				$where[$this->table.".{$p} >"] = 0;
			}
			else 
			{
				$where[$this->table.".{$p}"] = 0;
			}
		}
		
		return $where;
	}
	
	/**
	 * Tao filter tu input
	 */
	function filter_create($fields, &$input = array())
	{
		// Lay config
		$options = $this->_options;
		
		// Lay gia tri cua filter dau vao
		$input = array();
		foreach ($fields as $f)
		{
			$v = $this->input->get($f);
			$v = security_handle_input($v, in_array($f, array()));
			
			if (
				$f == 'option' && ! in_array($v, $options)
			)
			{
				$v = '';
			}
			
			$input[$f] = $v;
		}
		
		if ( ! empty($input['id']))
		{
			foreach ($input as $f => $v)
			{
				$input[$f] = ($f != 'id') ? '' : $v;
			}
		}
		
		// Tao bien filter
		$filter = array();
		$query 	= url_build_query($input, TRUE);
		foreach ($query as $f => $v)
		{
			switch ($f)
			{
				case 'option':
				{
					$f = $v;
					$v = TRUE;
					break;
				}
				case 'created':
				{
					$created_to = $input['created_to'];
					$v = (strlen($created_to)) ? array($v, $created_to) : $v;
					$v = get_time_between($v);
					$v = ( ! $v) ? NULL : $v;
					break;
				}
			}
			
			if ($v === NULL) continue;
			
			$filter[$f] = $v;
		}
		
		return $filter;
	}
	
	/**
	 * Tim kiem du lieu
	 */
	function _search($field, $key)
	{
		switch ($field)
		{
			case 'name':
			{
				$this->db->like($this->table.'.name', $key);
				break;
			}
		}
	}
	
}