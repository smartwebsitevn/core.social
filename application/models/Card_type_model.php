<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Card_type_model extends MY_Model
{
	public $table = 'card_type';
	public $order = array('sort_order', 'asc');
	public $timestamps = true;
	
	
	/**
	 * Filter handle
	 */
	public function _filter_get_where(array $filter)
	{
		$where = parent::_filter_get_where($filter);

		foreach (array('id', 'provider', 'status', 'created') as $p)
		{
			$f = (in_array($p, array())) ? $p.'_id' : $p;
			$f = $this->table.'.'.$f;
			$m = (in_array($p, array('created'))) ? 'range' : '';
			$this->_filter_set_where($filter, $p, $f, $where, $m);
		}
		
		if (isset($filter['name']))
		{
			$this->db->like('name', $filter['name']);
		}
		
		return $where;
	}
	
}