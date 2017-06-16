<?php
class Deposit_card_log_widget extends MY_Widget
{
	/**
	 * Khoi tao doi tuong
	 */
	public function __construct()
	{
		t('lang')->load('site/deposit_card_log');
	}
	
	/**
	 * Bang dieu khien cua thanh vien
	 */
	function newest($page_size = 20)
	{
	    $base_url = site_url('deposit_card_log');
	    
	    $user = user_get_account_info();
	    $filter = array();
	    $input = array();
	    
	    $filter['user_id'] = $user->id;
	    $filter['status']  = '1';

	    // Lay tong so
	    $total = model('deposit_card_log')->filter_get_total($filter);
	    $this->data['total'] 	= $total;
	  
	    $limit=0;
	    if($total>0){
	        $limit = $this->input->get('per_page');
	        $limit = max(0, min($limit, get_limit_page_last($total, $page_size)));
	    }
	    // Lay danh sach
	    $input['limit'] = array($limit, $page_size);

	    $list = model('deposit_card_log')->filter_get_list($filter, $input);
	    
	    // Luu bien gui den view
	    $this->data['list'] 	= $list;
	    
	   // Tao chia trang
		$pages_config = array();
		$pages_config['page_query_string'] = TRUE;
		$pages_config['base_url'] 	= $base_url.'?';
		$pages_config['total_rows'] = $total;
		$pages_config['per_page'] 	= $page_size;
		$pages_config['cur_page'] 	= $limit;
		$this->data['pages_config'] = $pages_config;
		
	    // Hien thi view
	    $this->load->view('tpl::_widget/deposit_card_log/newest', $this->data);
	}
	
	
}