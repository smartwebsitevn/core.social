<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Product_to_favorite_model extends MY_Model {
	public $table 	= 'product_to_favorite';


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
		foreach (array('id', 'product_id') as $key)
		{
			$this->_filter_parse_where( $key, $filter );
		}
		return $where;
	}


}