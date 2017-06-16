<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tax_rate_model extends MY_Model {
	
	var $table 	= 'tax_rate';
	//var $order = array('name', 'asc');
	var $order = array( array('name', 'asc') );
	public $translate_auto = TRUE;
	public $translate_fields = array(
		'name', 
	);
	
	/**
	 * Filter handle
	 */
	function _filter_get_where(array $filter)
	{
		$where = parent::_filter_get_where($filter);
		if (isset($filter['name']))
		{
			$this->search('tax_rate', 'name', $filter['name']);
		}
		if (isset($filter['geo_zone_id']))
		{
			if( is_array($filter['geo_zone_id']) )
				$this->db->where_in( $this->table.'.'.'geo_zone_id', $filter['geo_zone_id'] );
			else
				$this->db->where( $this->table.'.'.'geo_zone_id', $filter['geo_zone_id'] );
			
		}
		if (isset($filter['type']))
		{
			$where[$this->table.'.'.'type'] = $filter['type'];
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