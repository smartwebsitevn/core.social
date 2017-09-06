<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contact extends MY_Controller {
	
	/**
	 * Ham khoi dong
	 */
	function __construct()
	{
		parent::__construct();
		
		// Tai cac file thanh phan
		$this->load->model('contact_model');
		$this->lang->load('admin/contact');
	}
	
	/**
	 * Remap method
	 */
	function _remap($method)
	{
		if (in_array($method, array('view', 'del')))
		{
			$this->_action($method);
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
	function index()
	{
		$filter = array();
		$filter['type'] =  $this->_mod()->config("contact_type_contact");
		// Tao danh sach
		$this->_create_list($filter);
		$this->_display();
	}
	function register()
	{
		$filter = array();
		$filter['type'] =  $this->_mod()->config("contact_type_register");
		// Tao danh sach
		$this->_create_list($filter);
		$this->_display();
	}
	function order()
	{
		$filter = array();
		$filter['type'] =  $this->_mod()->config("contact_type_order");
		// Tao danh sach
		$this->_create_list($filter);
		$this->_display();
	}
	function index1()
	{

		// Tai cac file thanh phan
		$this->load->helper('form');
		
		// Tao filter
		$filter_input 	= array();
		$filter_fields 	= array('id', 'name', 'email', 'subject', 'created', 'created_to', 'read');
		$filter = $this->contact_model->filter_create($filter_fields, $filter_input);
		$this->data['filter'] = $filter_input;
		
		// Lay tong so
		$total = $this->contact_model->filter_get_total($filter);
		$page_size = config('list_limit', 'main');
		$limit = $this->input->get('per_page');
		$limit = max(0, min($limit, get_limit_page_last($total, $page_size)));
		
		// Lay danh sach
		$input = array();
		$input['limit'] = array($limit, $page_size);
		$list = $this->contact_model->filter_get_list($filter, $input);
		
		$actions = array('view', 'del');
		$list = admin_url_create_option($list, strtolower(__CLASS__), 'id', $actions);
		foreach ($list as $row)
		{
			$row->_created = get_date($row->created);
			$row->_created_full = get_date($row->created, 'full');
			foreach ($actions as $action)
			{
				$row->{'_can_'.$action} = ($this->_can_do($row, $action) && admin_permission_url($row->{'_url_'.$action})) ? TRUE : FALSE;
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
		
		
		// Tao action list
		$actions = array();
		foreach (array('del') as $v)
		{
			$url = admin_url(strtolower(__CLASS__).'/'.$v);
			if ( ! admin_permission_url($url)) continue;
			
			$actions[$v] = $url;
		}
		$this->data['actions'] = $actions;
		
		// Luu bien gui den view
		$this->data['action'] 	= current_url();
		$this->data['verify'] 	= config('verify', 'main');
		
		// Breadcrumbs
		$breadcrumbs = array();
		$breadcrumbs[] = array(admin_url('contact'), lang('mod_contact'));
		$breadcrumbs[] = array(current_url(), lang('list'));
		$this->data['breadcrumbs'] = $breadcrumbs;
		
		// Hien thi view
		$this->_display();
	}
	
	
/*
 * ------------------------------------------------------
 *  Action handle
 * ------------------------------------------------------
 */
	/**
	 * Thuc hien tuy chinh
	 */
	function _action($action)
	{
		// Lay input
		$ids = $this->uri->rsegment(3);
		$ids = ( ! $ids) ? $this->input->post('id') : $ids;
		$ids = ( ! is_array($ids)) ? array($ids) : $ids;
		
		// Thuc hien action
		foreach ($ids as $id)
		{
			// Xu ly id
			$id = ( ! is_numeric($id)) ? 0 : $id;
			
			// Kiem tra id
			$info = $this->contact_model->get_info($id);
			if ( ! $info) continue;
			
			// Kiem tra co the thuc hien hanh dong nay khong
			if ( ! $this->_can_do($info, $action)) continue;
			
			// Chuyen den ham duoc yeu cau
			$this->{'_'.$action}($info);
		}
	}
	
	/**
	 * Kiem tra co the thuc hien hanh dong hay khong
	 */
	function _can_do($info, $action)
	{
		switch ($action)
		{
			case 'view':
			case 'del':
			{
				return TRUE;
			}
		}
		
		return FALSE;
	}
	
	/**
	 * Xem chi tiet
	 */
	function _view($info)
	{
		// Gan trang thai
		if ($info->read == config('verify_no', 'main'))
		{
			$this->contact_model->update_field($info->id, 'read', config('verify_yes', 'main'));
		}
		
		// Xu ly thong tin
		$info->_created = get_date($info->created);
		$info->_created_full = get_date($info->created, 'full');
		
		// Luu bien gui den view
		$this->data['info'] = $info;
		
		// Hien thi view
		$this->_display('view', NULL);
	}
	
	/**
	 * Xoa du lieu
	 */
	function _del($info)
	{
		// Thuc hien xoa
		$this->contact_model->del($info->id);
		
		// Gui thong bao
		set_message(lang('notice_del_success'));
	}

	private function _create_list($filter = array(), $filter_fields = array(), $input = array())
	{
		// Lay gia tri cua filter dau vao
		$filter_input = array();
		if (!$filter_fields)
			$filter_fields 	= array('id', 'name', 'email', 'subject', 'created', 'created_to', 'read');
		$mod_filter = $this->_mod()->create_filter($filter_fields, $filter_input);
		$filter = array_merge($mod_filter, $filter);
		$this->data['filter'] = $filter_input;
		$total = $this->_model()->filter_get_total($filter);
		$page_size = config('list_limit', 'main');
		$limit = $this->input->get('per_page');
		$limit = min($limit, get_limit_page_last($total, $page_size));
		$limit = max(0, $limit);

		// Lay danh sach
		$input = array();
		$input['limit'] = array($limit, $page_size);
		$list = $this->_model()->filter_get_list($filter, $input);
		//pr_db($filter_input);
		$actions = array('view', 'del');
		$list = admin_url_create_option($list, strtolower(__CLASS__), 'id', $actions);
		foreach ($list as $row)
		{
			$row->_created = get_date($row->created);
			$row->_created_full = get_date($row->created, 'full');
			foreach ($actions as $action)
			{
				$row->{'_can_'.$action} = ($this->_can_do($row, $action) && admin_permission_url($row->{'_url_'.$action})) ? TRUE : FALSE;
			}
		}
		$this->data['list'] = $list;
		// Tao query chia trang
		$pages_query = array();
		foreach ($filter_input as $f => $v) {
			if (!$v) continue;
			$pages_query[$f] = $v;
		}

		$pages_query_export = $pages_query;
		$pages_query_export['export'] = 1;
		$pages_query_export = http_build_query($pages_query_export);

		$pages_query = http_build_query($pages_query);

		//export
		$this->data['url_export'] = current_url() . '?' . $pages_query_export;
		if ($this->input->get('export')) {
			if($filter['type'] == $this->_mod()->config("contact_type_contact"))
				$this->_export($list);
			else
				$this->_export_register($list);

			return;
		} else {
			// Tao chia trang
			$pages_config = array();
			$pages_config['page_query_string'] = TRUE;
			$pages_config['base_url'] = $this->_url() . '?' .$pages_query;
			$pages_config['total_rows'] = $total;
			$pages_config['per_page'] = $page_size;
			$pages_config['cur_page'] = $limit;
			$this->data['pages_config'] = $pages_config;
			$this->data['actions'] = array('del');
			$this->data['action'] = current_url();
			$this->data['verify'] = config('verify', 'main');
		}
	}

	function _export($list)
	{
		$headers = array(
			'stt'   => lang('stt'),
			'email'		=> lang('email'),
			'subject'		=> lang('subject'),
			'message'		=> lang('message'),
			'created'	=> lang('created'),
		);
		$lists = array();
		$i = 1;
		foreach ($list as $row) {

			$_list = array(
				'stt'   => $i,
				'email'   => $row->email,
				'subject'   => $row->subject,
				'message'   => $row->message,
				'created'   =>  $row->_created_full,
			);
			$lists[] = $_list;
			$i++;
		}

		$full_path = 'export/contact.xlsx';

		write_file($full_path);
		lib('phpexcel')->export($headers, $lists, './'.$full_path);
		// Khai bao du lieu tra ve
		$result['complete'] = TRUE;
		$result['location'] = base_url($full_path);
		set_output('json', json_encode($result));
		// redirect(base_url($full_path));

	}
	function _export_register($list)
	{
		$headers = array(
			'stt'   => lang('stt'),
			'name'		=> lang('name'),
			'phone'		=> lang('phone'),
			'email'		=> lang('email'),
			'address'		=> lang('address'),
			'message'		=> lang('message'),
			'created'	=> lang('created'),
		);
		$lists = array();
		$i = 1;
		foreach ($list as $row) {

			$_list = array(
				'stt'   => $i,
				'name'   => $row->name,
				'phone'   => $row->phone,
				'email'   => $row->email,
				'address'   => $row->address,
				'message'   => $row->message,
				'created'   =>  $row->_created_full,
			);
			$lists[] = $_list;
			$i++;
		}

		$full_path = 'export/contact.xlsx';

		write_file($full_path);
		lib('phpexcel')->export($headers, $lists, './'.$full_path);
		// Khai bao du lieu tra ve
		$result['complete'] = TRUE;
		$result['location'] = base_url($full_path);
		set_output('json', json_encode($result));
		// redirect(base_url($full_path));

	}
}