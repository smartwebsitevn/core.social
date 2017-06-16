<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bank_model extends MY_Model {
	
	var $table 	= 'bank';
	var $order 	= array('sort_order', 'asc');
	
	
	/**
	 * Filter handle
	 */
	public function _filter_get_where(array $filter)
	{
		$where = parent::_filter_get_where($filter);
	
		foreach (array('id') as $p)
		{
			$f = (in_array($p, array())) ? $p.'_id' : $p;
			$f = $this->table.'.'.$f;
			$m = (in_array($p, array('created'))) ? 'range' : '';
			$this->_filter_set_where($filter, $p, $f, $where, $m);
		}
		
		if (isset($filter['show']))
		{
			$where[$this->table.'.status'] = config('status_on', 'main');
		}
		
		return $where;
	}
	
}