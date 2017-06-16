<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Log_login extends MY_Controller {
	
	/**
	 * Ham khoi dong
	 */
	function __construct()
	{
		parent::__construct();
		
		// Tai cac file thanh phan
		$this->load->model('log_model');
		$this->lang->load('admin/log_login');
	}
	
	/**
	 * Remap method
	 */
	function _remap($method)
	{
		if (in_array($method, array('admin', 'user', 'index')))
		{
			$this->_list($method);
		}
		elseif (method_exists($this, $method))
		{
			$this->{$method}();
		}
		else
		{
			show_404('', FALSE);
		}
	}
	
	
/*
 * ------------------------------------------------------
 *  List handle
 * ------------------------------------------------------
 */
	/**
	 * Danh sach
	 */
	function _list($table)
	{
		// Xu ly table
		$table = ( ! in_array($table, array('admin', 'user'))) ? 'admin' : $table;
		
		// Tai cac file thanh phan
		$this->load->helper('form');
		$this->load->model($table.'_model');
		
		// Tao filter
		$filter_input 	= array();
		$filter_fields 	= array('acc', 'ip', 'created', 'created_to');
		$filter = $this->_filter_create($filter_fields, $filter_input);
		$filter['table'] 	= $table;
		$filter['action'] 	= 'login';
		$this->data['filter'] = $filter_input;
		
		// Lay tong so
		$total = $this->log_model->filter_get_total($filter);
		$page_size = config('list_limit', 'main');
		$limit = $this->input->get('per_page');
		$limit = max(0, min($limit, get_limit_page_last($total, $page_size)));
		
		// Lay danh sach
		$input = array();
		$input['limit'] = array($limit, $page_size);
		$list = $this->log_model->filter_get_list($filter, $input);
		foreach ($list as $row)
		{
			$row->_created = get_date($row->created);
			$row->_created_time = get_date($row->created, 'full');
			
			if ($table == 'admin')
			{
				$row->acc = $this->admin_model->get_info($row->table_id, 'id, username, email');
				$row->acc = admin_create_url('admin', $row->acc);
			}
			elseif ($table == 'user')
			{
				$row->acc = $this->user_model->get_info($row->table_id, 'id, email');
				$row->acc = admin_create_url('user', $row->acc);
			}
		}
		$this->data['list'] = $list;
		
		// Tao chia trang
		$pages_config = array();
		$pages_config['page_query_string'] = TRUE;
		$pages_config['base_url'] 	= current_url().'?'.url_build_query($filter_input);
		$pages_config['total_rows'] = $total;
		$pages_config['per_page'] 	= $page_size;
		$pages_config['cur_page'] 	= $limit;
		$this->data['pages_config'] = $pages_config;
		
		
		// Luu bien gui den view
		$this->data['action'] 	= current_url();
		$this->data['table'] 	= $table;
		$this->data['title'] 	= lang('title_log_login_'.$table);
		
		// Breadcrumbs
		$breadcrumbs = array();
		$breadcrumbs[] = array(current_url(), $this->data['title']);
		$this->data['breadcrumbs'] = $breadcrumbs;
		
		// Hien thi view
		$this->_display('list');
	}
	
	/**
	 * Tao filter tu input
	 */
	protected function _filter_create($fields, &$input = array())
	{
		// Lay gia tri cua filter dau vao
		$input = array();
		foreach ($fields as $f)
		{
			$v = $this->input->get($f);
			$v = security_handle_input($v, in_array($f, array()));
			
			$input[$f] = $v;
		}
		
		// Tao bien filter
		$filter = array();
		$query 	= url_build_query($input, TRUE);
		foreach ($query as $f => $v)
		{
			switch ($f)
			{
				case 'acc':
				{
					$f = 'table_id';
					break;
				}
				case 'created':
				{
					$created_to = $input['created_to'];
					$v = (strlen($created_to)) ? array($v, $created_to) : $v;
					$v = get_time_between($v);
					$v = ( ! $v) ? NULL : $v;
					break;
				}
			}
			
			if ($v === NULL) continue;
			
			$filter[$f] = $v;
		}
		
		return $filter;
	}
	
}