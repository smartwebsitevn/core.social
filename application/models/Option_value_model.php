<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Option_value_model extends MY_Model {

	public $table 	= 'option_value';
	public $order	= array( array('sort_order', 'asc'), array('id', 'desc'));
	public $translate_auto = FALSE;
	public $translate_fields = array();

	

	

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