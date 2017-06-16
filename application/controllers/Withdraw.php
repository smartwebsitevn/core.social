<?php

use App\Withdraw\Handler\Form\WithdrawPayment\WithdrawPaymentFormHandler;
use App\Withdraw\Handler\Form\WithdrawPayment\WithdrawPaymentFormRequest;
use App\Invoice\Model\InvoiceOrderModel as InvoiceOrderModel;
use App\Withdraw\WithdrawFactory as WithdrawFactory;
use App\Currency\Model\CurrencyModel as CurrencyModel;

class Withdraw extends MY_Controller
{
	/**
	 * Khoi tao doi tuong
	 */
	public function __construct()
	{
		parent::__construct();

		if ( ! user_is_login())
		{
			redirect_login_return();
		}

		t('lang')->load('modules/withdraw/common');
		t('lang')->load('site/withdraw');
	}

	/**
	 * Form
	 */
	public function index()
	{
	    //kiem tra da co bank chua
	    $user = user_get_account_info();
	    $filter = array();
	    $filter['user'] = $user->id;
	    $filter['status'] = mod('order')->status('completed');
	    $banks = model('user_bank')->filter_get_list($filter);
	    if(empty($banks))
	    {
	        set_message(lang('you_need_to_add_a_bank_teller_before'));
	        redirect(site_url('user_bank/add'));
	    }
	    $list = $this->_list_order('WithdrawPayment');
	    $this->data['orders']  = $list;
	    
	    $fee_constant = module_get_setting('withdraw', 'fee_constant');
	    $fee_percent = module_get_setting('withdraw', 'fee_percent');
	    $this->data['fee_constant'] = currency_format_amount($fee_constant);
	    $this->data['fee_percent']  = $fee_percent;
	     
		$this->_makeForm('form');
	}

	/**
	 * Confirm
	 */
	public function confirm()
	{
		$this->_makeForm('confirm');
	}

	/**
	 * Tao form xu ly
	 *
	 * @param string $page
	 */
	public function _makeForm($page)
	{
		$input = t('input')->post();
		$input['page'] = $page;

		$request = new WithdrawPaymentFormRequest($input);

		$form = new WithdrawPaymentFormHandler($request);

		$this->_run_form_handler($form, $page);
	}


	/**
	 * Tao list order theo key
	 *
	 * @param string $type
	 * @param array  $cat_ids
	 * @return array
	 */
	private function _list_order($service_key)
	{
	    $user = user_get_account_info();
	
	    $filter = [
	        'user_id'     => $user->id,
	        'service_key' => $service_key,
	        //'tran_status' => 'paid'
	    ];
	     
	    $input = array();
	    $input['limit'] = array(10, 0);
	    $list = model('invoice_order')->filter_get_list($filter, $input);
	
	    return InvoiceOrderModel::makeCollection($list);
	}
	
	/**
	 * lay phi rut tien
	 *
	 */
	public function load_fee()
	{
	    $purse_number   = t('input')->post('purse_number');
	    $bank_withdraw  = t('input')->post('bank_withdraw');
	    $purse = model('purse')->get_info_rule(array('number' => $purse_number));
	    $CurrencyModel = new CurrencyModel;
	    $fee_constant  = 0;
	    $fee_percent   = 0;
	    if($purse)
	    {
	        $currency = $CurrencyModel->find($purse->currency_id);
	        //phi chung
	        $fee = WithdrawFactory::withdraw()->getFeeSetting($currency);
            //phi theo bank
	        $fee_bank = WithdrawFactory::withdraw()->getBankFeeSetting($currency, $bank_withdraw);
	        
	        $fee_constant = $fee['constant'] + $fee_bank['constant'];
	        $fee_percent  = $fee['percent'] + $fee_bank['percent'];  
	    }
	    $data = array();
	    $data['fee_constant'] = currency_format_amount($fee_constant);
	    $data['fee_percent']  = $fee_percent;
	    $data = json_encode($data);
	    
	    set_output('json', $data);
	}
	
	
}
