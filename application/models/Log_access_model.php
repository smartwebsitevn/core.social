<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Log_access_model extends MY_Model {
	
	var $table = 'log_access';
	
	
	/**
	 * Luu log
	 */
	function add($table, $table_id)
	{
		$data = array();
		$data['table'] 		= $table;
		$data['table_id'] 	= $table_id;
		$data['url'] 		= current_url(TRUE);
		$data['ruri'] 		= $this->uri->ruri_string();
		$data['ip'] 		= $this->input->ip_address();
		$data['user_agent'] = $this->input->user_agent();
		$data['created'] 	= now();
		$this->create($data);
	}
	
	/**
	 * Filter handle
	 */
	function _filter_get_where(array $filter)
	{
		$where = parent::_filter_get_where($filter);
		
		foreach (array('id', 'table', 'table_id', 'ip', 'created') as $p)
		{
			$f = (in_array($p, array())) ? $p.'_id' : $p;
			$f = $this->table.'.'.$f;
			$m = (in_array($p, array('created'))) ? 'range' : '';
			$this->_filter_set_where($filter, $p, $f, $where, $m);
		}
		
		foreach (array('url', 'ruri') as $p)
		{
			if (isset($filter[$p]))
			{
				$this->search($this->table, $p, $filter[$p]);
			}
		}
		
		return $where;
	}
	
	/**
	 * Tao filter tu input
	 */
	function filter_create($fields, &$input = array())
	{
		// Lay gia tri cua filter dau vao
		$input = array();
		foreach ($fields as $f)
		{
			$v = $this->input->get($f);
			$v = security_handle_input($v, in_array($f, array()));
			
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
			case 'url':
			{
				$this->db->like($this->table.'.url', $key);
				break;
			}
			
			case 'ruri':
			{
				$this->db->like($this->table.'.ruri', $key);
				break;
			}
		}
	}
	
	/**
	 * Don dep du lieu
	 */
	function cleanup($table, $table_id = '', $timeout = 0)
	{
		$timeout = ( ! $timeout) ? 30*24*60*60 : $timeout;
		
		$where = array();
		$where['table'] = $table;
		if ($table_id != '')
		{
			$where['table_id'] = $table_id;
		}
		$where['created <'] = now() - $timeout;
		
		$this->del_rule($where);
	}
	
}