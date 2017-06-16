<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Product_to_special_model extends MY_Model {
	public $table 	= 'product_to_special';
	public $order	= array( 'sort', 'ASC' );
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
		
		if (isset($filter['id']))
		{
			$where[$this->table.'.'.'id'] = $filter['id'];
		}

		if (isset($filter['product_id']))
		{
			$where[$this->table.'.'.'product_id'] = $filter['product_id'];
		}
	
		if (isset($filter['customer_group_id']))
		{
			$where[$this->table.'.'.'customer_group_id'] = $filter['customer_group_id'];
		}

		return $where;
	}

}