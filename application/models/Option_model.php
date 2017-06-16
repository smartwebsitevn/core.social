<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class Option_model extends MY_Model {

	
	public $table 	= 'option';
	public $order	= array( array('sort_order', 'asc'), array('id', 'desc'));
	public $translate_auto = TRUE;
	public $translate_fields = array(
		'name'
	);

	

	

/*
 * ------------------------------------------------------
 *  Main handle
 * ----------------------------------------------------
 */

	/**
	 * Filter handle
	 */

	function _filter_get_where(array $filter)
	{
		$where = parent::_filter_get_where($filter);
		
		if (isset($filter['id']))
		{
			if( is_array($filter['id']) )
				$this->db->where_in($this->table.'.id', $filter['id']);
			else
				$where[$this->table.'.'.'id'] = $filter['id'];
		}


		if (isset($filter['id_nega']))
			$where[$this->table.'.'.'id <>'] = $filter['id_nega'];

		if (isset($filter['name']))
			$this->search($this->table, 'name', $filter['name']);


		if (isset($filter['status']))
		{
			if( $filter['status'] != -1 )
			$where[$this->table.'.'.'status'] = $filter['status'];
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