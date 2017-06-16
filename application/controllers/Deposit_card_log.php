<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Deposit_card_log extends MY_Controller
{
	/**
	 * Ham khoi dong
	 */
	public function __construct()
	{
		parent::__construct();

		$this->lang->load('site/'.$this->_get_mod());
	}
	
	/**
	 * Remap method
	 */
	public function _remap($method, $params = array())
	{
		return $this->_remap_action($method, $params, array('view'));
	}
	
	/**
	 * List
	 */
	public function index()
	{
	    if ( ! user_is_login())
	    {
	        redirect_login_return();
	    }
	    
	    $user = user_get_account_info();

	    $this->data['statuss'] = $this->_mod()->statuss();
		
		$this->data['providers'] = $this->_get_providers();
		$this->data['types'] =  model('card_type')->get_list();
		
		$list = array();
		$list['filter'] = true;
		$list['filter_fields'] = array('id', 'provider', 'type', 'code', 'serial', 'created', 'user_id', 'status', 'created_to');
		$list['filter_value']  = array('user_id' => $user->id, 'status' => '1');
		
		$list['input'] = ['relation' => 'user'];
		$list['actions'] = array('view', 'active');

		$this->data['stats'] = $this->_makeStatsList($list);

		$this->_list($list);  
	}

	/**
	 * Tao sums
	 *
	 * @param array $list_args
	 * @return array
	 */
	protected function _makeStatsList(array $list_args)
	{
		$filter = $this->_mod()->create_filter($list_args['filter_fields']);

		$filter = array_merge($filter, $list_args['filter_value']);

		$sums = [];

		foreach (['amount'] as $param)
		{
			$sums[$param] = $this->_model()->filter_get_sum($param, $filter);

			$sums['format_'.$param] = currency_format_amount_default($sums[$param]);
		}

		return $sums;
	}
	/**
	 * Lay danh sach providers
	 *
	 * @return array
	 */
	protected function _get_providers()
	{
	    $list = model('payment_card')->get_list_installed();
	    $list = model('payment_card')->get_list_info($list);
	
	    return $list;
	}
	
	
	/**
	 * View
	 */
	protected function _view($info)
	{
		$info = $this->_mod()->add_info($info);
		
		pr($info);
	}
	
}