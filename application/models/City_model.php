<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class City_model extends MY_Model {
	
	var $table 	= 'city';
	//var $order = array('name', 'asc');
	var $order = array(array('country_id', 'asc'),array('name', 'asc'));
	
	/**
	 * Filter handle
	 */
	function _filter_get_where(array $filter)
	{
		$where = parent::_filter_get_where($filter);
		if (isset($filter['country_id']))
		{
			$where[$this->table.'.'.'country_id'] = $filter['country_id'];
		}
		if (isset($filter['show']))
		{
			if( $filter['show'] != -1 )
			$where[$this->table.'.'.'show'] = $filter['show'];
		}

		if (isset($filter['name']))
		{
			$this->search('city', 'name', $filter['name']);
		}
		
		return $where;
	}

	/**
	 * Tao filter tu input
	 */
	function filter_create($fields, &$input = array())
	{
		/* Lay gia tri cua filter dau vao */
		$input = array();
		foreach ($fields as $f)
		{
			$v = $this->input->get($f);
			$v = security_handle_input($v, in_array($f, array()));
			
			$input[$f] = $v;
		}
		
		/* Tao bien filter */
		$filter = array();
		$query 	= url_build_query($input, TRUE);
		foreach ($query as $f => $v)
		{
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
?>