<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Product_owner_model extends MY_model
{

	public $table 	= 'product_owner';
	public $order	= array( ['',''] );
	public $translate_auto = FALSE;
	public $translate_fields = array();

	

	

/*
 * ------------------------------------------------------
 *  Main handle
 * ----------------------------------------------------
 */

	/**
	 * Filter handle
	 * 
	 */
	function _filter_get_where(array $filter)
	{
		$where = parent::_filter_get_where($filter);
		
		foreach ( array( 'owner_id', 'table_id', 'table_name' ) as $key ) 
		{
			$this->_filter_parse_where( $key, $filter );
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
			
			// Bỏ dấu , khi lọc theo giá
			if( $v && in_array( $f, array( 'price_gt', 'price_lt' ) ) ) 
			{
				$tmp = urldecode($v);
				$tmp = str_replace( ',', '', $tmp);
				$input[$f] = (double)$tmp;
				continue;
			}

			
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