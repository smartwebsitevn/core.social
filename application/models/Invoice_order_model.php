<?php

class Invoice_order_model extends MY_Model
{
	public $table = 'invoice_order';

	public $select = 'invoice_order.*';

	public $join_sql = [
		'invoice' => 'invoice_order.invoice_id = invoice.id',
		'user' => 'invoice.user_id = user.id',
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
			'id', 'invoice_id', 'user_id', 'user_ip', 'service_key', 'amount', 'profit',
			'invoice_status', 'order_status'
		] as $p)
		{
			$f = (in_array($p, [])) ? $p.'_id' : $p;
			$f = $this->table.'.'.$f;
			$m = (in_array($p, ['amount', 'profit'])) ? 'range' : '';
			$this->_filter_set_where($filter, $p, $f, $where, $m);
		}

		if (isset($filter['key']))
		{
			$query = $this->_make_sql_filter_by_key($filter['key']);

			t('db')->where($query);
		}
		// loc tuy bien
		if (isset($filter['service_key_custom']))
		{
			$services=null;
			switch($filter['service_key_custom']){
				case "Deposit":
					$services= ["DepositAdmin","DepositBank","DepositCard","DepositPayment"];
					break;
				case "Order":
					$services= ["ProductOrderCard",
						"ProductOrderShip",
						"ProductOrderTopupGame",
						"ProductOrderTopupMobile",
						"ProductOrderTopupMobilePost",
					];
					break;

				case "Other":
					$services= ["TransferReceive",
						"TransferSend",
						"WithdrawAdmin",
						"WithdrawPayment",
					];
					break;
			}
			//pr($services);
			if($services)
				$this->db->where_in($this->table.'.service_key' ,$services);
		}

		//=== Su ly loc theo ngay tao
		//  1: tu ngay  - den ngay
		if (isset($filter['created']) && isset($filter['created_to'])) {
			$where[$this->table . '.created >='] = is_numeric($filter['created']) ? $filter['created'] : get_time_from_date($filter['created']);
			$where[$this->table . '.created <='] = is_numeric($filter['created_to']) ? $filter['created_to'] : get_time_from_date($filter['created_to']) + 24 * 60 * 60;// phai cong them 1 ngay de thoi gian no la cuoi cua ngay hien thoi
		} //2: tu ngay
		elseif (isset($filter['created'])) {
			if(is_array($filter['created']))
			{
				$where[$this->table . '.created >='] = $filter['created'][0] ;
				$where[$this->table . '.created <='] = $filter['created'][0] ;
			}
			else
				$where[$this->table . '.created >='] = is_numeric($filter['created']) ? $filter['created'] : get_time_from_date($filter['created']);
		} //3: den ngay
		elseif (isset($filter['created_to'])) {
			$where[$this->table . '.created <='] = is_numeric($filter['created_to']) ? $filter['created_to'] : get_time_from_date($filter['created_to']) + 24 * 60 * 60;// phai cong them 1 ngay de thoi gian no la cuoi cua ngay hien thoi
		}

		// fix invoice_service =''
		$where[$this->table . '.service_key !='] = '';
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
		$query = ["`keywords` LIKE '%" . t('db')->escape_like_str($key) . "%'"];

		//==
		$keys = preg_replace('/\s+/', ' ', $key);
		$keys = explode(' ', $keys);
		foreach ($keys as $v) {
			$v = t('db')->escape_like_str($v);

			$query[] = "`keywords` LIKE '%{$v}%'";
		}
		$query = implode(' OR ', $query);
		return "($query)";
	}

}