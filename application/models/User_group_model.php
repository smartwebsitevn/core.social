<?php

class User_group_model extends MY_Model
{
	public $table = 'user_group';

	public $order = ['sort_order', 'asc'];

	public function _filter_get_where(array $filter)
	{
		$where = parent::_filter_get_where($filter);
		//pr($this->fields_filter );
		foreach ($this->fields_filter as $key) {
			if (isset($filter[$key]) && $filter[$key] != -1) {
				$this->_filter_parse_where($key, $filter);
			}
		}
		if (isset($filter['show']))
		{
			$where[$this->table.'.status'] = 1;
		}

		return $where;
	}
}