<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Deposit_card_log_model extends MY_Model
{
	public $table = 'deposit_card_log';
	public $timestamps = true;

	public $relations = array(
		'user' => 'one',
	);
	
	
	/**
	 * Filter handle
	 */
	public function _filter_get_where(array $filter)
	{
		$where = parent::_filter_get_where($filter);
	
		foreach (array(
			'id', 'provider', 'type', 'code', 'serial', 'amount',
			'status', 'user_id', 'ip', 'created',
		) as $p)
		{
			$f = (in_array($p, array())) ? $p.'_id' : $p;
			$f = $this->table.'.'.$f;
			$m = (in_array($p, array('amount', 'created'))) ? 'range' : '';
			$this->_filter_set_where($filter, $p, $f, $where, $m);
		}

		/* foreach (array('code', 'serial') as $p)
		{
			if (isset($filter[$p]))
			{
				$this->db->like($this->table.'.'.$p, $filter[$p]);
			}
		} */
		
		return $where;
	}
	
}