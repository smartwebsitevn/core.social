<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Product_to_addon_model extends MY_Model {
	public $table 	= 'product_to_addon';
	public $order	= array( 'sort', 'ASC' );
	public $translate_auto = FALSE;
	public $translate_fields = array(
	);

	public $fields_filter = array(
		//== core
		'id','!id','id_gt', 'id_gte', 'id_lt', 'id_lte',
		'product_id','!product_id',
		'addon_id','!addon_id',
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

		//pr($filter);
		foreach ($this->fields_filter as $key) {
			if (isset($filter[$key]) && $filter[$key] != -1) {
				//echo '<br>key='.$key.', v='.$filter[$key];
				$this->_filter_parse_where($key, $filter);
			}
		}

		return $where;
	}

}