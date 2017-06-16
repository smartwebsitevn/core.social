<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_bank_model extends MY_Model {
	
	var $table 	= 'user_bank';
	
	public $select = 'user_bank.*, user.phone AS user_phone, user.email AS user_email, bank.name';
	
	public $order =  array('last_update', 'desc');
	
	public $join_sql = [
	    'bank' => 'user_bank.bank_id = bank.id',
	    'user' => 'user_bank.user_id = user.id',
	];
	
	/**
	 * Filter handle
	 */
	public function _filter_get_where(array $filter)
	{
		$where = parent::_filter_get_where($filter);
	
		foreach (array('id','user','bank','city', 'status') as $p)
		{
			$f = (in_array($p, array('user','bank','city'))) ? $p.'_id' : $p;
			$f = $this->table.'.'.$f;
			$m = (in_array($p, array('created'))) ? 'range' : '';
			$this->_filter_set_where($filter, $p, $f, $where, $m);
		}
		
		if (isset($filter['created']))
		{
		    $where['user_bank.created >='] = $filter['created'][0];
		    $where['user_bank.created <'] = $filter['created'][1];
		}
		
		return $where;
	}
	
	
	
}