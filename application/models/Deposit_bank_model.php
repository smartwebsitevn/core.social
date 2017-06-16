<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Deposit_bank_model extends MY_Model
{
	public $table = 'deposit_bank';
	public $timestamps = true;

	public $relations = array(
		'user' => 'one',
	);
	
	
	/**
	 * Filter handle
	 */
	function _filter_get_where(array $filter)
	{
		$where = parent::_filter_get_where($filter);
		
		foreach (array('id', 'user', 'status', 'created') as $p)
		{
			$f = (in_array($p, array('user'))) ? $p.'_id' : $p;
			$f = $this->table.'.'.$f;
			$m = (in_array($p, array('created'))) ? 'range' : '';
			$this->_filter_set_where($filter, $p, $f, $where, $m);
		}
		
		return $where;
	}
	
}