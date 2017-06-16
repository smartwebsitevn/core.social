<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tran_model extends MY_Model {
	
	public $table = 'tran';

	public $select = 'tran.*';

	public $join_sql = [
		'invoice' => 'tran.invoice_id = invoice.id',
		'user' => 'tran.user_id = user.id',
	];

	public $relations = [
		'invoice' => 'one',
		'user' => 'one',
	];


	/**
	 * Filter handle
	 */
	function _filter_get_where(array $filter)
	{
		$where = parent::_filter_get_where($filter);

		foreach ([
			'id', 'invoice_id', 'status', 'payment_id', 'payment_tran_id',
			'currency_id', 'user_id', 'user_ip', 'created',
		] as $p)
		{
			$f = (in_array($p, [])) ? $p.'_id' : $p;
			$f = $this->table.'.'.$f;
			$m = (in_array($p, ['created'])) ? 'range' : '';
			$this->_filter_set_where($filter, $p, $f, $where, $m);
		}

		return $where;
	}

	function get_sum($field, $where = array())
	{
		$this->db->select_sum($field);
		$this->db->where($where);
		$this->db->from('tran');
		
		$row = $this->db->get()->row();
		
		$sum = 0;
		foreach ($row as $f => $v)
		{
			$sum = $v;
		}
		
		return $sum;
	}

	function filter_get_sum($field, $filter)
	{
		$where = $this->_filter_get_where($filter);
		
		return $this->get_sum($field, $where);
	}

}