<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class emailsmtp_model extends MY_Model {
	
	var $table 	= 'emailsmtp';
	var $order 	= array('email', 'asc');
	
	
	/**
	 * Filter handle
	 */
	public function _filter_get_where(array $filter)
	{
		$where = parent::_filter_get_where($filter);

		
		return $where;
	}
	
}