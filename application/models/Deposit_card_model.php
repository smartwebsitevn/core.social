<?php

class Deposit_card_model extends MY_Model
{
	public $table = 'deposit_card';

	public $select = 'deposit_card.*';

	public $timestamps = true;

	public $join_sql = [
		'invoice'       => 'deposit_card.invoice_id = invoice.id',
		'invoice_order' => 'deposit_card.invoice_order_id = invoice_order.id',
		'purse'         => 'deposit_card.purse_id = purse.id',
		'card_type'     => 'deposit_card.card_type_id = card_type.id',
		'user'          => 'deposit_card.user_id = user.id',
	];

	public $relations = [
		'invoice'       => 'one',
		'invoice_order' => 'one',
		'purse'         => 'one',
		'card_type'     => 'one',
		'user'          => 'one',
	];

	/**
	 * Filter handle
	 */
	function _filter_get_where(array $filter)
	{
		$where = parent::_filter_get_where($filter);

		foreach ([
		 	'id', 'invoice_id', 'invoice_order_id', 'purse_id', 'provider', 'card_type_id',
		 	'card_code', 'card_serial', 'user_id', 'status', 'created'
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
