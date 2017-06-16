<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Topup_offline_model extends MY_Model {
	
	public $table 	= 'topup_offline';

	public $join_sql = array(
		'invoice' => 'topup_offline.id = invoice.id',
		'topup_offline_order' => 'topup_offline.id = topup_offline_order.topup_offline_id',
	);
	
	public $relations = array(
		'invoice' => array('one', 'id', 'id'),
		'topup_offline_order' => 'many',
	);
	
	
	/**
	 * Filter handle
	 */
	function _filter_get_where(array $filter)
	{
		$where = parent::_filter_get_where($filter);

		// Table order
		foreach (array('id', 'amount', 'status') as $p)
		{
			$f = (in_array($p, array())) ? $p.'_id' : $p;
			$f = $this->table.'.'.$f;
			$m = (in_array($p, array('amount'))) ? 'range' : '';
			$this->_filter_set_where($filter, $p, $f, $where, $m);
		}
		
		// Table invoice
		$fs = array(
			'invoice_status' => 'status',
			'invoice_amount' => 'amount',
		);
		
		foreach (array('invoice_status', 'user', 'invoice_amount', 'payment', 'created') as $p)
		{
			$f = array_get($fs, $p, $p);
			$f = (in_array($f, array('user'))) ? $f.'_id' : $f;
			$f = 'invoice.'.$f;
			$m = (in_array($p, array('amount', 'created'))) ? 'range' : '';
			$this->_filter_set_where($filter, $p, $f, $where, $m);
		}
		
		return $where;
	}
	
}