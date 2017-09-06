<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login_history extends MY_Controller {
	
	function __construct()
	{
		parent::__construct();
		
		// Tai cac file thanh phan
		$this->load->model('login_history_model');
		$this->lang->load('admin/login_history');
	}
	
	/**
	 * Danh sach
	 */
	function index()
	{
		// Lay type hien tai
		$type = strtolower($this->uri->rsegment(3));
		$type = ($type != 'user') ? 'admin' : $type;
		$this->data['type'] = $type;
		
		// Lay is_admin
		$is_admin = ($type == 'admin') ? 'yes' : 'no';
		$is_admin = config('verify_'.$is_admin, 'main');
		
		
		// Lay gia tri cua filter dau vao
		$filter_input = array();
		$filter_fields = array('user', 'user_name', 'ip', 'time');
		foreach ($filter_fields as $f)
		{
			$v = $this->input->get($f);
			
			$filter_input[$f] = $v;
		}
		$this->data['filter'] = $filter_input;
		
		// Tao bien filter
		$filter = array();
		$filter['is_admin'] = $is_admin;
		foreach ($filter_input as $f => $v)
		{
			if (!$v) continue;
			
			switch ($f)
			{
				case 'time':
				{
					$v = get_time_from_date($v);
					if (!$v) continue;
					break;
				}
			}
			
			$filter[$f] = $v;
		}
		
		
		// Lay tong so
		$total = $this->login_history_model->filter_get_total($filter);
		$page_size = config('list_limit', 'main');
		$limit = $this->input->get('per_page');
		$limit = min($limit, $total-fmod($total,$page_size));
		$limit = max(0, $limit);
		
		// Lay danh sach
		$input = array();
		$input['order'] = array('id', 'desc');;
		$input['limit'] = array($limit, $page_size);
		$list = $this->login_history_model->filter_get_list($filter, $input);
		foreach ($list as $row)
		{
			$row->_time = get_date($row->time);
			$row->_time_time = get_date($row->time, 'time');
		}
		$this->data['list'] = $list;
		
		
		// Tao url chinh
		$main_url = admin_url('login_history/index/'.$type);
		
		// Tao query chia trang
		$pages_query = array();
		foreach ($filter_input as $f => $v)
		{
			if (!$v) continue;
			$pages_query[$f] = $v;
		}
		$pages_query = http_build_query($pages_query);
		
		// Tao chia trang
		$pages_config = array();
		$pages_config['page_query_string'] = TRUE;
		$pages_config['base_url'] 	= $main_url.'?'.$pages_query;
		$pages_config['total_rows'] = $total;
		$pages_config['per_page'] 	= $page_size;
		$pages_config['cur_page'] 	= $limit;
		$this->data['pages_config'] = $pages_config;
		
		
		// Breadcrumbs
		$breadcrumbs = array();
		$breadcrumbs[] = array('', $this->lang->line('group_account'));
		$breadcrumbs[] = array(admin_url('login_history'), $this->lang->line('mod_login_history'));
		$breadcrumbs[] = array('', $this->lang->line('list'));
		$this->data['breadcrumbs'] = $breadcrumbs;
		
		// Message
		$message = $this->session->flashdata('flash_message');
		if ($message)
		{
			$this->data['message'] = $message;
		}
		
		// Tao cac bien gui den view
		$this->data['action'] = $main_url;
		$this->data['url_search_user'] = ($is_admin) ? admin_url('admin/search_auto') : admin_url('user/search_auto');
		
		// Hien thi view
		$this->data['temp'] = 'admin/login_history/index';
		$this->load->view('admin/main', $this->data);
	}
	
}