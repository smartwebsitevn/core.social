<?php

class Payment_model extends MY_Model
{
	public $table = 'payment';

	public $order = ['sort_order', 'asc'];

	/**
	 * Filter handle
	 */
	public function _filter_get_where(array $filter)
	{
		$where = parent::_filter_get_where($filter);

		foreach (['id', 'key'] as $p)
		{
			$f = (in_array($p, [])) ? $p.'_id' : $p;
			$f = $this->table.'.'.$f;
			$m = (in_array($p, [])) ? 'range' : '';
			$this->_filter_set_where($filter, $p, $f, $where, $m);
		}

		if (isset($filter['name']))
		{
			$this->db->like('name', $filter['name']);
		}

		return $where;
	}

}