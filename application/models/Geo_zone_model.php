<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$obj = &get_instance();
$obj->load->model('Extend_model');
class Geo_zone_model extends Extend_model {
	
	var $table 	= 'geo_zone';
	//var $order = array('name', 'asc');
	var $order = array( array('name', 'asc') );
	public $translate_auto = TRUE;
	public $translate_fields = array(
		'name', 
		'description'
	);
	
	/**
	 * Filter handle
	 */
	function _filter_get_where(array $filter)
	{
		$where = parent::_filter_get_where($filter);
		if (isset($filter['name']))
		{
			$this->search('geo_zone', 'name', $filter['name']);
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