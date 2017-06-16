<?php

class Log_balance_model extends MY_Model
{
	public $table = 'log_balance';

	public $select = 'log_balance.*';

	public $join_sql = [
		'purse' => 'log_balance.purse_id = purse.id',
		'user'  => 'log_balance.user_id = user.id',
	];

	public $relations = [
		'purse' => 'one',
		'user'  => 'one',
	];

	/**
	 * Filter handle
	 */
	function _filter_get_where(array $filter)
	{
		$where = parent::_filter_get_where($filter);

		foreach ([
			'id', 'status', 'purse_id', 'reason_key', 'user_id',
			'currency_id', 'ip', 'created'
		] as $p)
		{
			$f = (in_array($p, [])) ? $p.'_id' : $p;
			$f = $this->table.'.'.$f;
			$m = (in_array($p, ['created'])) ? 'range' : '';
			$this->_filter_set_where($filter, $p, $f, $where, $m);
		}

		return $where;
	}

}