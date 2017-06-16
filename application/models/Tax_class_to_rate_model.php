<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Tax_class_to_rate_model extends MY_Model {

	
	public $table 	= 'tax_class_to_rate';
	public $order	= array( 'piority', 'Desc' );
	public $translate_auto = FALSE;
	public $translate_fields = array(
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
		
		if (isset($filter['class_id']))
		{
			$where[$this->table.'.'.'class_id'] = $filter['class_id'];
		}
	
		if (isset($filter['rate_id']))
		{
			$where[$this->table.'.'.'rate_id'] = $filter['rate_id'];
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