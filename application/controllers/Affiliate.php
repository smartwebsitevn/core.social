<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Affiliate extends MY_Controller {
	
	/**
	 * Ham khoi dong
	 */
	function __construct()
	{
		parent::__construct();
		if (!mod("product")->setting('affiliate_turn_on')) {
			redirect();
		}
		// Tai cac file thanh phan
		$this->load->model('user_model');
		$this->lang->load('site/affiliate');
		$this->lang->load('site/user');
	}


	/**
	 * Referred members
	 */
	function index()
	{
		// Neu chua dang nhap
		if ( ! user_is_login())
		{
			redirect_login_return();
		}

		// Tai cac file thanh phan
		$this->load->helper('form');

		// Lay thong tin thanh vien
		$user = user_get_account_info();

		// Tao bien filter
		$filter = array();
		$filter['user_affiliate_id'] = $user->id;

		// Lay tong so
		$total = $this->user_model->filter_get_total($filter);
		$page_size = config('list_limit', 'main');
		$limit = $this->input->get('per_page');
		$limit = min($limit, get_limit_page_last($total, $page_size));
		$limit = max(0, $limit);

		// Lay danh sach
		$input = array();
		$input['limit'] = array($limit, $page_size);
		$list = $this->user_model->filter_get_list($filter, $input);
		foreach ($list as $row)
		{
			$row->_created = get_date($row->created, 'full');
		}
		$this->data['list'] = $list;

		// Tao url chinh
		$base_url = site_url('affiliate');

		// Tao query chia trang
		$pages_query  = array();
		$filter_input = array();
		foreach ($filter_input as $f => $v)
		{
			if ( ! $v) continue;
			$pages_query[$f] = $v;
		}
		$pages_query = http_build_query($pages_query);

		// Tao chia trang
		$pages_config = array();
		$pages_config['page_query_string'] = TRUE;
		$pages_config['base_url'] 	= $base_url.'?'.$pages_query;
		$pages_config['total_rows'] = $total;
		$pages_config['per_page'] 	= $page_size;
		$pages_config['cur_page'] 	= $limit;
		$this->data['pages_config'] = $pages_config;


		// Breadcrumbs
		$breadcrumbs = array();
		$breadcrumbs[] = array(current_url(), lang('mod_affiliate'));
		$this->data['breadcrumbs'] = $breadcrumbs;

		// Luu cac bien gui den view
		$this->data['action'] 	= current_url();
		$this->data['affiliate_link'] = site_url('ref-'.$user->username);

		// Hien thi view
		$this->_display();
	}


	/**
	 * Affiliate link
	 */
	function link()
	{
		// Lay input
		$user_key = $this->uri->rsegment(3);
		// Kiem tra user
		$user = model('user')->find_user($user_key);
		$user_check=true;
		if ( ! $user)
		{
			set_message(lang('notice_account_affiliate_invalid'));
			//redirect();
			$user_check=false;
		}

		if ( $user->blocked == config('verify_yes', 'main')  ) {
			set_message(lang('notice_account_affiliate_invalid'));

			//redirect();
			$user_check=false;
		}
		if (mod("user")->setting('register_require_activation') && $user->activation == config('verify_no', 'main')) {
			set_message(lang('notice_account_affiliate_invalid'));
			//redirect();
			$user_check=false;
		}
		// Luu session

		if($user_check)
			$this->session->set_userdata('user_affiliate', $user_key);
		// Chuyen den trang dang ki
		redirect(site_url('user/register'));
		//redirect(site_url());
	}


}