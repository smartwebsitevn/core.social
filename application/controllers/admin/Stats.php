<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Stats extends MY_Controller
{
	/**
	 * Ham khoi dong
	 */
	public function __construct()
	{
		parent::__construct();
		
		$this->lang->load('admin/stats');
	}
	
	/**
	 * Bao cao
	 */
	public function report()
	{
		if (t('input')->post('_submit'))
		{
			$input = $this->_report_get_input();
			
			$data = $this->_report_get_data($input);
			
			$this->_form_submit_output(array(
				'complete' 	=> true,
				'date'		=> array('start' => $input['date'][0], 'end' => $input['date'][1]),
				'data' 		=> $data,
				'_data' 	=> $this->_report_format_data($data),
				'url' 		=> $this->_report_get_url($input),
			));
		}
	}
	
	/**
	 * Lay report input
	 * 
	 * @return array
	 */
	protected function _report_get_input()
	{
		$start 	= t('input')->post('start');
		$end	= t('input')->post('end');
		
		$date = array();
		$time = get_time_between(array($start, $end), $date);
		if ( ! $time)
		{
			$default = $this->_mod()->get_date_default();
			
			$time = get_time_between(array($default['start'], $default['end']), $date);
		}
		
		return compact('time', 'date');
	}
	
	/**
	 * Lay report data
	 * 
	 * @param array $input
	 * @return array
	 */
	protected function _report_get_data(array $input)
	{
		list($start, $end) = $input['time'];
		
		$data = array();
		
		$where = array();
		
		foreach (array('code', 'topup_mobile', 'topup_game', 'topup_mobile_post') as $p)
		{
			$_where = array_merge($where, array(
				'order.product_type' => config('product_type_'.$p, 'main'),
			));
			
			$data['order_'.$p] = $this->_mod()->get_report_table('order', 'amount', $start, $end, $_where);
		}

		$data['deposit_amount'] = 0;
		foreach (array('deposit', 'deposit_card') as $p)
		{
			$_where = array_merge($where, array(
				'tran.type' => config('tran_type_'.$p, 'main'),
			));
			
			$data['deposit_amount'] += $this->_mod()->get_report_table('tran', 'amount', $start, $end, $_where);
		}
		
		$data['revenue'] = $this->_report_get_revenue($data);
		
		return $data;
	}
	
	/**
	 * Format report data
	 * 
	 * @param array $data
	 * @return array
	 */
	protected function _report_format_data(array $data)
	{
		foreach ($data as $p => $v)
		{
			$data[$p] = currency_convert_format_amount($v);
		}
		
		return $data;
	}
	
	/**
	 * Lay report revenue
	 * 
	 * @param array $data
	 * @return float
	 */
	protected function _report_get_revenue(array $data)
	{
		$revenue = 0;
		foreach ($data as $p => $v)
		{
			if (starts_with($p, 'order_') || in_array($p, array()))
			{
				$revenue += $data[$p];
			}
		}
		
		return $revenue;
	}
	
	/**
	 * Lay report data
	 * 
	 * @param array $input
	 * @return array
	 */
	protected function _report_get_url(array $input)
	{
		list($start, $end) = $input['date'];
		
		$query = array(
			'created' => $start,
			'created_to' => $end,
		);
		
		$result = array();
		
		foreach (array('code', 'topup_mobile', 'topup_game', 'topup_mobile_post') as $p)
		{
			$result['order_'.$p] = admin_url('order').'?'.http_build_query(array_merge($query, array(
				'type' => $p,
				'tran_status' => 'completed',
			)));
		}
	
		$result['deposit_amount'] = admin_url('tran').'?'.http_build_query(array_merge($query, array(
			'type' 		=> 'deposit_card',
			'status' 	=> 'completed',
		)));
		
		return $result;
	}
	
}