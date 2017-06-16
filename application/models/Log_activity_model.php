<?php

class Log_activity_model extends MY_Model
{
	public $table = 'log_activity';

	/**
	 * Filter handle
	 */
	public function _filter_get_where(array $filter)
	{
		$where = parent::_filter_get_where($filter);

		foreach (['id', 'logger_key', 'action', 'key', 'owner_type', 'owner_key', 'ip', 'created'] as $p)
		{
			$f = (in_array($p, array())) ? $p.'_id' : $p;
			$f = $this->table.'.'.$f;
			$m = (in_array($p, array('created'))) ? 'range' : '';
			$this->_filter_set_where($filter, $p, $f, $where, $m);
		}

		return $where;
	}

}