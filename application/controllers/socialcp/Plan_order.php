<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class plan_order extends MY_Controller {
	
	/**
	 * Ham khoi dong
	 */
	function __construct()
	{
		parent::__construct();
		
		// Tai cac file thanh phan
		$this->load->helper('site');
		$this->load->model('plan_model');
		$this->lang->load('admin/plan_order');
		//$this->lang->load('tran');
		// Tai cac file thanh phan
		$this->load->helper('tran');
		$this->load->model('plan_order_model');
		
		
	}

	
	/**
	 * Danh sach da upload
	 */
	function index()
	{
		// Tai file thanh phan
		$this->load->helper('file');
		
		// Lay config
		$statuss = config('verify', 'main');
		$this->data['statuss'] = $statuss;
		
		
		// Lay gia tri cua filter dau vao
		$filter_input = array();
		$filter_fields = array('name', 'status', 'tran_user_id');
		foreach ($filter_fields as $f)
		{
			$v = $this->input->get($f);
					
			if ($f == 'status' && !in_array($v, $statuss))
			{
				$v = $statuss[1];
			}
			
			$filter_input[$f] = $v;
		}
		$this->data['filter'] = $filter_input;
		
		// Tao bien filter
		$filter = array();
		foreach ($filter_input as $f => $v)
		{
			if (!$v) continue;
					
			switch ($f)
			{
				case 'status':
				{
					$v = config('verify_'.$v, 'main');
					break;
				}
			}
			
			$filter[$f] = $v;
		}
		
		
		// Lay tong so
		$total = $this->plan_order_model->filter_get_total($filter);
		$page_size = config('list_limit', 'main');
		$limit = $this->input->get('per_page');
		$limit = min($limit, get_limit_page_last($total, $page_size));
		$limit = max(0, $limit);
		
		$tran_status = config('tran_statuss', 'main');
		
		// Lay danh sach
		$input = array();
		$input['limit'] = array($limit, $page_size);
		$list = $this->plan_order_model->filter_get_list($filter, $input);
		foreach ($list as $row)
		{
			$plan_info = @unserialize($row->plan_info);
			$plan_info->cost = floatval($plan_info->cost);
			$plan_info->cost = (!$plan_info->cost) ? '' : $plan_info->cost;
			$plan_info->_cost    = currency_format_amount($plan_info->cost);
			$plan_info->cost_new = $plan_info->cost;
				
			if($plan_info->discount > 0)
			{
				$discount = $plan_info->discount;
				$plan_info->discount_percent = $discount;
			
				if($plan_info->discount_type == config('discount_type_percent', 'main'))
				{
					$cost_new  = $plan_info->cost - (($plan_info->cost*$discount)/100);
				}else{
					//neu chiet khau theo so tien
					$cost_new = $plan_info->cost - $discount;
					$plan_info->discount_percent = $discount*100/$plan_info->cost;
				}
				$plan_info->discount_percent = round($plan_info->discount_percent,0);
			
				$plan_info->cost_new = $cost_new;
			}
				
			$plan_info->_cost_new = currency_format_amount($plan_info->cost_new);
			
			$row->plan_info = $plan_info;
			
			$row->_tran_created = get_date($row->tran_created);
			$row->_tran_status  = $tran_status[$row->tran_status];
			
			$row->_url_view = admin_url('tran/action/view/'.$row->tran_id);
		}
		$this->data['list'] = $list;
		
		
		
		// Tao query chia trang
		$pages_query = array();
		foreach ($filter_input as $f => $v)
		{
			if (!$v) continue;
			$pages_query[$f] = $v;
		}
		$pages_query = http_build_query($pages_query);
		
		$base_url = site_create_url('user_plan');
		
		// Tao chia trang
		$pages_config = array();
		$pages_config['page_query_string'] = TRUE;
		$pages_config['base_url'] 	= $base_url.'?'.$pages_query;
		$pages_config['total_rows'] = $total;
		$pages_config['per_page'] 	= $page_size;
		$pages_config['cur_page'] 	= $limit;
		$this->data['pages_config'] = $pages_config;
		
		
		// Message
		$message = $this->session->flashdata('flash_message');
		if ($message)
		{
			$this->data['message'] = $message;
		}
		
		        
		// Breadcrumbs
		$breadcrumbs = array();
		$breadcrumbs[] = array(admin_url('plan_order'), lang('mod_plan_order'));
		$breadcrumbs[] = array('', lang('list'));
		$this->data['breadcrumbs'] = $breadcrumbs;
		
		// Message
		$message = $this->session->flashdata('flash_message');
		if ($message)
		{
			$this->data['message'] = $message;
		}
		
		// Luu cac bien gui den view
        $this->data['status']   = $statuss;
		$this->data['action'] 	= current_url();
		$this->data['url_search_plan_order'] = admin_url('plan_order/ac');
		
		// Hien thi view
		$this->data['temp'] = 'admin/plan_order/index';
		$this->load->view('admin/main', $this->data);
	}

}