<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends MY_Controller {
	
	/**
	 * Ham khoi dong
	 */
	function __construct()
	{
		parent::__construct();
		
		// Tai cac file thanh phan
		$this->load->model('admin_model');
		$this->lang->load('admin/admin');
	}

	/**
	 * Remap method
	 */
	function _remap($method)
	{
		if (in_array($method, array('edit', 'del', 'matrix_reset')))
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
 *  Rules params
 * ------------------------------------------------------
 */
	/**
	 * Gan dieu kien cho cac bien
	 */
	function _set_rules($params = array())
	{
		$rules = array();
		$rules['username'] 			= array('username', 'trim|xss_clean|alpha_dash|callback__check_username');
		$rules['password'] 			= array('password', 'required|trim|xss_clean|min_length[6]');
		$rules['password_repeat'] 	= array('password_repeat', 'required|trim|xss_clean|matches[password]');
		$rules['name'] 				= array('name', 'required|trim|xss_clean');
		$rules['phone'] 				= array('phone', 'required|trim|xss_clean');
		$rules['email'] 				= array('email', 'required|trim|xss_clean');
		$rules['admin_group'] 		= array('admin_group', 'callback__check_admin_group');
		
		$this->form_validation->set_rules_params($params, $rules);
	}
	
	/**
	 * Kiem tra username nay da duoc su dung chua
	 */
	function _check_username($value)
	{
		$act = $this->_get_act();
		$id = $this->uri->rsegment(3);
		if($act == 'edit'  && $id) {
			$admin_info = admin_get_info($id);

			if ($admin_info->is_root)
				return true;
		}
		elseif($act == 'profile') {
			$admin_info = admin_get_account_info();
			if ($admin_info->is_root)
				return true;
		}
		//neu ko phai la root thi kiem tra  username
		if(empty($value))
		{
			$this->form_validation->set_message(__FUNCTION__, lang('notice_value_invalid'));
			return FALSE;
		}
		$id = (!is_numeric($id)) ? 0 : $id;
		
		$where = array();
		$where['id !='] = $id;
		$where['username'] = $value;
		$id = $this->admin_model->get_id($where);
		
		if ($id)
		{
			$this->form_validation->set_message(__FUNCTION__, lang('notice_already_used'));
			return FALSE;
		}

		// kiem tra neu thay username ma khong doi pass thi bao loi
		if($act == 'edit') {
			if ($admin_info->username != $value) {
				$password = $this->input->post('password');
				if (!$password) {
					$this->form_validation->set_message(__FUNCTION__, lang('notice_change_username_require_change_pass'));
					return FALSE;
				}
			}
		}
		return TRUE;
	}
	
	/**
	 * Kiem tra admin_group
	 */
	function _check_admin_group($value)
	{
		$act = $this->_get_act();
		$id = $this->uri->rsegment(3);
		if($act == 'edit'  && $id) {
			$admin_info = admin_get_info($id);
			if ($admin_info->is_root)
				return true;
		}
		elseif($act == 'profile') {
			$admin_info = admin_get_account_info();
			if ($admin_info->is_root)
				return true;
		}




		$this->load->model('admin_group_model');
		
		$info = $this->admin_group_model->get_info($value);
		if (!$info)
		{
			$this->form_validation->set_message(__FUNCTION__, lang('notice_value_not_exist'));
			return FALSE;
		}

		$admin =admin_get_account_info();
		// khong cho phep gan group la supper admin cho thanh vien khac
		// hoac cap quuyen vuot qua quyen han cua minh
		if( $value == '1' || $info->level > $admin->level)
		{
			$this->form_validation->set_message(__FUNCTION__, lang('notice_value_invalid'));
			return FALSE;
		}

		return TRUE;
	}
	
	/**
	 * Tu dong kiem tra gia tri cua bien
	 */
	function _autocheck($param)
	{
		$this->_set_rules($param);
		
		$result = array();
		$result['accept'] = $this->form_validation->run();
		$result['error'] = form_error($param);

		$output = json_encode($result);
		set_output('json', $output);
	}
	
	
/*
 * ------------------------------------------------------
 *  Action handle
 * ------------------------------------------------------
 */
 	/**
	 * Chinh sua
	 */
	function profile()
	{

		// Tai cac file thanh phan
		$this->load->library('form_validation');
		$this->load->helper('form');
		$info = admin_get_account_info();

		// Tu dong kiem tra gia tri cua 1 bien
		$param = $this->input->post('_autocheck');
		if ($param)
		{
			$this->_autocheck($param);
		}

		// Xu ly form
		if ($this->input->post('_submit'))
		{
			// Gan dieu kien cho cac bien
			$params = array('username', 'name','phone','email', 'admin_group');

			$password = $this->input->post('password');
			if ($password)
			{
				array_push($params, 'password', 'password_repeat');
			}

			$this->_set_rules($params);

			// Xu ly du lieu
			$result = array();
			if ($this->form_validation->run())
			{

				//pr($info);
				if($info->is_root){
					$username = 'system';
					$group= 1;
				}else{
					// Lay username
					$username = $this->input->post('username');
					$group =$this->input->post('admin_group');
				}


				// Cap nhat vao data
				$data = array();
				$data['username'] = $username;
				if ($password)
				{
					$password = security_encode($password, strtolower($username));
					$data['password'] = $password;
				}
				foreach(array('name','phone','email','yahoo','skype','birthday','address','desc','blocked') as $f){
					$data[$f]			= $this->input->post($f);
				}
				$data['admin_group_id']	= $group;
				$this->admin_model->update($info->id, $data);

				// Khai bao du lieu tra ve
				$result['complete'] = TRUE;
				$result['location'] = admin_url('admin');
				set_message(lang('notice_update_success'));
			}
			else
			{
				foreach ($params as $param)
				{
					$result[$param] = form_error($param);
				}
			}

			// Form output
			$this->_form_submit_output($result);
		}


		// Lay danh sach admin_group
		$this->load->model('admin_group_model');
		$this->data['admin_group'] = $this->admin_group_model->get_list();


		// Luu bien gui den view
		$this->data['action'] 	= current_url();
		$this->data['info'] 	= $info;

		// Tao cay thu muc
		$breadcrumbs = array();
		$breadcrumbs[] = array('', lang('group_account'));
		$breadcrumbs[] = array(admin_url('admin'), lang('mod_admin'));
		$breadcrumbs[] = array(current_url(), lang('profile'));
		$this->data['breadcrumbs'] = $breadcrumbs;

		// Hien thi view
		$this->_display('form');
	}

	/**
	 * Them moi
	 */
	function add()
	{
		// Tai cac file thanh phan
		$this->load->library('form_validation');
		$this->load->helper('form');
		
		// Tu dong kiem tra gia tri cua 1 bien
		$param = $this->input->post('_autocheck');
		if ($param)
		{
			$this->_autocheck($param);
		}
		
		// Xu ly form
		if ($this->input->post('_submit'))
		{
			// Gan dieu kien cho cac bien
			$params = array('username', 'password', 'password_repeat', 'name','phone','email', 'admin_group');
			$this->_set_rules($params);
			
			// Xu ly du lieu
			$result = array();
			if ($this->form_validation->run())
			{
				// Lay username
				$username = $this->input->post('username');
				
				// Xu ly password
				$password = $this->input->post('password');
				$password = security_encode($password, strtolower($username));
				
				// Cap nhat vao data
				$data = array();
				$data['username']		= $username;
				$data['password']		= $password;

				foreach(array('name','phone','email','yahoo','skype','birthday','address','desc','blocked') as $f){
					$data[$f]			= $this->input->post($f);
				}
				$data['admin_group_id'] = $this->input->post('admin_group');
				
				// Lay admin_id vua them
				$admin_id = 0;
				$this->admin_model->create($data, $admin_id);
				
				// Tao the xac thuc
				$this->admin_model->matrix_create($admin_id);
				
				// Khai bao du lieu tra ve
				$result['complete'] = TRUE;
				$result['location'] = admin_url('admin');
				set_message(lang('notice_add_success'));
			}
			else
			{
				foreach ($params as $param)
				{
					$result[$param] = form_error($param);
				}
			}
			
			// Form output
			$this->_form_submit_output($result);
		}
		
		
		// Lay danh sach admin_group
		$this->load->model('admin_group_model');
		$this->data['admin_group'] = $this->admin_group_model->get_list(array('where'=>array('id <>'=>1)));

		// Luu bien gui den view
		$this->data['action'] = current_url();
		
		// Tao cay thu muc
		$breadcrumbs = array();
		$breadcrumbs[] = array('', lang('group_account'));
		$breadcrumbs[] = array(admin_url('admin'), lang('mod_admin'));
		$breadcrumbs[] = array(current_url(), lang('add'));
		$this->data['breadcrumbs'] = $breadcrumbs;
		
		// Hien thi view
		$this->_display('form');
	}
	
	/**
	 * Chinh sua
	 */
	function _edit($info)
	{
		// Tai cac file thanh phan
		$this->load->library('form_validation');
		$this->load->helper('form');
		
		// Tu dong kiem tra gia tri cua 1 bien
		$param = $this->input->post('_autocheck');
		if ($param)
		{
			$this->_autocheck($param);
		}
		
		// Xu ly form
		if ($this->input->post('_submit'))
		{

			// Gan dieu kien cho cac bien
			$params = array('username', 'name','phone','email',  'admin_group');
			
			$password = $this->input->post('password');
			if ($password)
			{
				array_push($params, 'password', 'password_repeat');
			}
			
			$this->_set_rules($params);
			
			// Xu ly du lieu
			$result = array();
			if ($this->form_validation->run())
			{
				if($info->is_root){
					$username = 'system';
					$group = 1;
				}else{
					// Lay username
					$username = $this->input->post('username');
					$group =$this->input->post('admin_group');
				}

				// Cap nhat vao data
				$data = array();
				$data['username'] = $username;
				if ($password)
				{
					$password = security_encode($password, strtolower($username));
					$data['password'] = $password;
				}
				foreach(array('name','phone','email','yahoo','skype','birthday','address','desc','blocked') as $f){
					$data[$f]			= $this->input->post($f);
				}
				$data['admin_group_id']	= $group;
				$this->admin_model->update($info->id, $data);
				
				// Khai bao du lieu tra ve
				$result['complete'] = TRUE;
				$result['location'] = admin_url('admin');
				set_message(lang('notice_update_success'));
			}
			else
			{
				foreach ($params as $param)
				{
					$result[$param] = form_error($param);
				}
			}
			
			// Form output
			$this->_form_submit_output($result);
		}
		
		
		// Lay danh sach admin_group
		$this->load->model('admin_group_model');
		$this->data['admin_group'] = $this->admin_group_model->get_list(array('where'=>array('id <>'=>1)));

		// Luu bien gui den view
		$this->data['action'] 	= current_url();
		$this->data['info'] 	= $info;
		
		// Tao cay thu muc
		$breadcrumbs = array();
		$breadcrumbs[] = array('', lang('group_account'));
		$breadcrumbs[] = array(admin_url('admin'), lang('mod_admin'));
		$breadcrumbs[] = array(current_url(), lang('add'));
		$this->data['breadcrumbs'] = $breadcrumbs;
		
		// Hien thi view
		$this->_display('form');
	}

	/**
	 * Xoa du lieu
	 */
	function _del($info)
	{
		// Thuc hien xoa
		$this->admin_model->del($info->id);
		
		// Gui thong bao
		set_message(lang('notice_del_success'));
	}
	
	/**
	 * Reset the xac thuc
	 */
	function _matrix_reset($info)
	{
		// Thuc hien reset
		$this->admin_model->matrix_create($info->id);
		
		// Gui thong bao
		set_message(lang('notice_update_success'));
	}
	
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
			$info = admin_get_info($id);
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
		$admin =admin_get_account_info();

		if($admin->id != $info->id){
			//pr($admin);
			if($admin->level <= $info->level )
				return false;
		}
		switch ($action)
		{
			case 'edit':{
				return TRUE;
			}
			case 'del':
			{
				if( $info->is_root){
					return FALSE;
				}
				return TRUE;
			}

			case 'matrix_reset':
			{
				return TRUE;
			}
		}
		
		return FALSE;
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
		// Tai file thanh phan
		$this->load->model('admin_group_model');
		
		// Lay danh sach
		$input = array();
		$input['order'] = array('id', 'asc');
		$list = $this->admin_model->get_list($input);

		$actions = array('edit', 'del', 'matrix_reset');
		$list = admin_url_create_option($list, strtolower(__CLASS__), 'id', $actions);
		foreach ($list as $row)
		{
			$row = admin_add_info($row);
			$row->balance 	= $this->admin_model->balance_encrypt('decode', $row->id, $row->balance);
			$row->_balance 	= currency_convert_format_amount($row->balance);
			$row->matrix 		= $this->admin_model->matrix_get($row->id);
			$row->admin_group 	= $this->admin_group_model->get_info($row->admin_group_id);
			
			foreach ($actions as $action)
			{
				$row->{'_can_'.$action} = ($this->_can_do($row, $action) && admin_permission_url($row->{'_url_'.$action})) ? TRUE : FALSE;
			}
		}
		$this->data['list'] = $list;
		
		// Luu cac bien gui den view
		$this->data['action']		= current_url();
		$this->data['url_search'] 	= admin_url('admin/ac');
		
		// Breadcrumbs
		$breadcrumbs = array();
		$breadcrumbs[] = array('', lang('group_account'));
		$breadcrumbs[] = array(admin_url('admin'), lang('mod_admin'));
		$breadcrumbs[] = array(current_url(), lang('list'));
		$this->data['breadcrumbs'] = $breadcrumbs;
		
		// Hien thi view
		$this->_display();
	}
	
	/**
	 * Tim kiem tu dong (autocomplete)
	 */
	function ac()
	{
		$keyword = $this->input->get('term');
		
		$filter = array();
		$filter['username'] = $keyword;
		$input = array();
		$input['select'] 	= 'id, username';
		$input['order'] 	= array('username', 'asc');
		$input['limit'] 	= array(0, config('list_auto_limit', 'main'));
		$list = $this->admin_model->filter_get_list($filter, $input);
		
		$result = array();
		foreach ($list as $row)
		{
			$item = array();
			$item['id'] = $row->id;
			$item['label'] = $row->username;
			$item['value'] = $row->username;
			
			$result[] = $item;
		}
		
		$output = json_encode($result);
		set_output('json', $output);
	}
	
}