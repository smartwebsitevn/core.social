<?php

class User_group_model extends MY_Model
{
	public $table = 'user_group';

	public $order = ['sort_order', 'asc'];


	public $fields_filter = array(
		'name', 'type',
	);
	public function _filter_get_where(array $filter)
	{
		$where = parent::_filter_get_where($filter);
		foreach ($this->fields_filter as $key) {
			if(in_array($key,['created','created_to'])) continue;
			if (isset($filter[$key]) && $filter[$key] != -1) {
				$this->_filter_parse_where($key, $filter);
			}
		}
		//=== Su ly loc theo time
		$where= $this->_filter_parse_time_where($filter,$where );

		if (isset($filter['show']))
		{
			$where[$this->table.'.status'] = 1;
		}

		return $where;
	}
}