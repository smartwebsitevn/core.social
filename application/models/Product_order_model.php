<?php

class Product_order_model extends MY_Model
{
	public $table = 'product_order';

	/*public $join_sql = [
	    //'invoice'  => 'service_order.invoice_id = invoice.id',
	    'invoice_order' => 'service_order.invoice_order_id = invoice_order.id',
	    'user'     => 'service_order.user_id = user.id',
	];
	
	public $relations = [
	    //'invoice'  => 'one',
	    'invoice_order' => 'one',
	    'user'     => 'one',
	];
	*/
	
	/**
	 * Filter handle
	 */
	public function _filter_get_where(array $filter)
	{
		$where = parent::_filter_get_where($filter);

		foreach ([
			'id', 'invoice', 'type', 'invoice_order', 'user',  'status', 'created',
		] as $p)
		{
			$f = in_array($p, ['invoice', 'invoice_order', 'user', ]) ? $p.'_id' : $p;
			$f = $this->table.'.'.$f;
			$m = in_array($p, ['created']) ? 'range' : '';
			$this->_filter_set_where($filter, $p, $f, $where, $m);
		}
       
	    if (isset($filter['key']))
		{
			$query = $this->_make_sql_filter_by_key($filter['key']);

			t('db')->where($query);
		}
		if (isset($filter['expire']))
		{
			$v = ($filter['expire']) ;//? 'on' : 'off';
			if(config('verify_'.$v, 'main'))
				$where[$this->table.'.'.'expire_to <'] = now();
			else
				$where[$this->table.'.'.'expire_to >'] = now();

		}
		return $where;
	}

	/**
	 * Tao query filter theo key
	 *
	 * @param string $key
	 * @return string
	 */
	protected function _make_sql_filter_by_key($key)
	{
		$key = str_replace([',', '.'], '', $key);
		$key = trim($key);

		$query = ["`keywords` LIKE '%".t('db')->escape_like_str($key)."%'"];

		$keys = preg_replace('/\s+/', ' ', $key);
		$keys = explode(' ', $keys);

		$query_sub = [];

		foreach ($keys as $v)
		{
			$v = t('db')->escape_like_str($v);

			$query_sub[] = "`keywords` LIKE '%{$v}%'";
		}

		$query[] = '('.implode(' AND ', $query_sub).')';
      
		return implode(' OR ', $query);
	}
		
}