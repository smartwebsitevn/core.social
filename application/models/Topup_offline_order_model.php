<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Topup_offline_order_model extends MY_Model {
	
	var $table 	= 'topup_offline_order';

	public $join_sql = array(
		'invoice' => 'topup_offline_order.topup_offline_id = invoice.id',
	);
	
	public $relations = array(
		'topup_offline' => 'one',
	);


	/**
	 * Lay danh sach cua order
	 * 
	 * @param int $order_id
	 * @param string $select
	 * @return array
	 */
	public function get_list_order($order_id, $select = '*')
	{
		$input = array();
		$input['select'] = $select;
		$input['where']['topup_offline_id'] = $order_id;
		
		return $this->get_list($input);
	}
	
	/**
	 * Filter handle
	 */
	function _filter_get_where(array $filter)
	{
		$where = parent::_filter_get_where($filter);
		
		// Table order
		foreach (array('id', 'topup_offline') as $p)
		{
			$f = (in_array($p, array('topup_offline'))) ? $p.'_id' : $p;
			$f = $this->table.'.'.$f;
			$m = (in_array($p, array())) ? 'range' : '';
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