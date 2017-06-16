<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Geo_zone_to_city_model extends MY_Model {
	
	var $table 	= 'geo_zone_to_city';
	
	/**
	 * Filter handle
	 */
	function _filter_get_where(array $filter)
	{
		$where = parent::_filter_get_where($filter);
		if (isset($filter['geo_zone_id']))
		{
			$where[$this->table.'.'.'geo_zone_id'] = $filter['geo_zone_id'];
		}

		if (isset($filter['country_id']))
		{
			$this->db->where($this->table.'.'.'country_id', $filter['country_id']);
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


}
?>