<?php

use App\Invoice\Library\OrderStatus;

class Stats_widget extends MY_Widget
{
	/**
	 * Khoi tao doi tuong
	 */
	public function __construct()
	{
		t('lang')->load('modules/stats/stats');
	}

	/**
	 * Thong ke
	 *
	 * @param array $opts
	 */
	public function stats(array $opts = [])
	{
		//$a= model('user')->get_total(array('blocked'=>1));
		//echo $a;pr_db();
		$list = [
			'total_balance' => currency_format_amount_default($this->getTotalBalance()),
			'total_user' => model('user')->get_total(),
			'total_user_activation' => model('user')->get_total(array('activation'=>'1')),
			'total_user_verify' => model('user')->get_total(array('verify'=>1)),
			'total_user_blocked' => model('user')->get_total(array('blocked'=>'1')),

		];

		$this->data['list'] = $list;

		$this->_display($this->_make_view(array_get($opts, 'view'), __FUNCTION__));
	}

	/**
	 * Lay tong so du cua cac purse
	 *
	 * @return float
	 */
	protected function getTotalBalance()
	{
		$list = t('db')
			->select_sum('balance_decode', 'balance')
			->select('currency_id')
			->group_by('currency_id')
			->get('purse')->result();

		$total = 0;

		foreach ($list as $row)
		{
			$total += currency_convert_amount_default($row->balance, $row->currency_id);
		}

		return $total;
	}
	/**
	 * Yeu cau cho xu ly
	 *
	 * @param array $opts
	 */
	public function request(array $opts = [])
	{
	    $modules = [/*'withdraw',*/ 'deposit_bank'];
	
	    $list = [];
	
	    foreach ($modules as $module)
	    {
	        $method = 'get'.studly_case($module).'Request';
	
	        if ( ! method_exists($this, $method)) continue;
	
	        $list[$module] = call_user_func([$this, $method]);
	    }
	
	    $service_keys  = array(
			'DepositPayment', 'DepositBank',
			//'ProductOrderCard', 'ProductOrderTopupMobile', 'ProductOrderTopupMobilePost', 'ProductOrderTopupGame'
			);
	    foreach ($service_keys as $service_key)
	    {
	        $filter = array();
	        $filter = [
	            'service_key'     => $service_key,
	            'invoice_status'  => (!in_array($service_key, array('DepositBank'))) ? 'paid' : 'unpaid',
	            'order_status'    => 'pending',
	        ];
	
	        $total = model('invoice_order')->filter_get_total($filter);
	        $items = array();
	        $items['name']  = lang($service_key);
	        $items['total'] = $total;
	        $items['url']   = admin_url('invoice_order').'?'.http_build_query($filter);
	        $list[$service_key]  = $items;
	    }
	
	
	    $this->data['list'] = $list;
	
	    $this->_display($this->_make_view(array_get($opts, 'view'), __FUNCTION__));
	}
	

	/**
	 * Lay thong tin yeu cau rut tien
	 *
	 * @return array
	 */
	protected function getWithdrawRequest()
	{
		return [

			'name' => 'Yêu cầu rút tiền',

			'total' => model('withdraw')->get_total([
				'method' => 'payment',
				'status' => OrderStatus::PENDING,
			]),

			'url' => admin_url('invoice_order').'?'.http_build_query([
				'service_key' => 'WithdrawPayment',
				'order_status' => OrderStatus::PENDING,
			]),

		];
	}
}