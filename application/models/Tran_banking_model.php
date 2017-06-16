<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH.'models/core/Core_info_key_model.php';

class Tran_banking_model extends Core_info_key_model
{
	public $table = 'tran_banking';
	public $select = 'tran_banking.*';
	
	public $relations = array(
		'tran' => array('one', 'id', 'id'),
	);
	
	public $join_sql = array(
		'tran' => 'tran_banking.id = tran.id',
	);

	
	/**
	 * Filter handle
	 */
	function _filter_get_where(array $filter)
	{
		$where = parent::_filter_get_where($filter);

		// Table withdraw
		foreach (array('id', 'bank', 'sender_acc_id', 'receiver_acc_id', 'amount') as $p)
		{
			$f = (in_array($p, array('bank'))) ? $p.'_id' : $p;
			$f = $this->table.'.'.$f;
			$m = (in_array($p, array('created', 'amount'))) ? 'range' : '';
			$this->_filter_set_where($filter, $p, $f, $where, $m);
		}
		
		if (isset($filter['acc_id']))
		{
			$v = $this->db->escape_like_str($filter['acc_id']);
			
			$this->db->where("(
				( {$this->table}.sender_acc_id LIKE '%{$v}%' ) OR 
				( {$this->table}.receiver_acc_id LIKE '%{$v}%' )
			)");
		}
		
		
		// Table tran
		$fs = array(
			'tran_amount' => 'amount',
		);
		
		foreach (array('status', 'user', 'tran_amount', 'created') as $p)
		{
			$f = array_get($fs, $p, $p);
			$f = (in_array($f, array('user'))) ? $f.'_id' : $f;
			$f = 'tran.'.$f;
			$m = (in_array($p, array('created', 'amount'))) ? 'range' : '';
			$this->_filter_set_where($filter, $p, $f, $where, $m);
		}
		
		return $where;
	}
	
}