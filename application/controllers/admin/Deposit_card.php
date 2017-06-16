<?php

use App\Deposit\Model\DepositCardModel;
use App\User\UserFactory;

class Deposit_card extends MY_Controller
{
	/**
	 * Khoi tao doi tuong
	 */
	public function __construct()
	{
		parent::__construct();

		t('lang')->load('modules/deposit/common');
		t('lang')->load('modules/deposit/deposit_card');
		t('lang')->load('admin/deposit_card');
		
	}

	/**
	 * List
	 */
	public function index()
	{
		$filter = [];

		$user_key = t('input')->get('user_key');

		if ($user = UserFactory::user()->find($user_key))
		{
			$filter['user_id'] = $user->id;
		}

		$filter_fields = [
			'id', 'invoice_id', 'invoice_order_id', 'purse_id', 'provider', 'card_type_id',
			'card_code', 'card_serial', 'user_id', 'user_key', 'status', 'created', 'created_to',
		];

		$this->_list([
			'filter' => true,
			'filter_fields' => $filter_fields,
			'filter_value' => $filter,
			'input' => ['relation' => ['purse', 'card_type', 'user']],
//			'order' => true,
//			'order_fields' => ['id', 'service_key', 'order_status', 'amount', 'profit', 'created'],
			'actions' => [],
			'actions_list' => [],
			'display' => false,
		]);

		$this->data['list'] = DepositCardModel::makeCollection($this->data['list']);

		$this->data['providers'] = $this->_get_providers();
		$this->data['types'] =  model('card_type')->get_list();
		
		
		//thong ke
		// Tao filter
		$filter_input 	= array();
		$filter = $this->_mod()->create_filter($filter_fields, $filter_input);
		//tong so tien the nap
		$amount = $this->_model()->filter_get_sum('amount', $filter);
		$this->data['amount'] = currency_convert_format_amount($amount);
		 
		
		//tong so tien lợi nhuận
		$profit_amount = $this->_model()->filter_get_sum('profit_amount', $filter);
		$this->data['profit_amount'] = currency_convert_format_amount($profit_amount);
			
		//tong so tien đã nạp cho khách
		$this->data['amount_discount'] = currency_convert_format_amount($amount - $profit_amount);
		 
		
		$this->data['add_view'] = array(2, 'admin/deposit_card/stats', $this->data);
		
		$this->_display();
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
	
}
