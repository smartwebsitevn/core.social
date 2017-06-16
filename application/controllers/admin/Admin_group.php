<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_group extends MY_Controller {
	
	/**
	 * Ham khoi dong
	 */
	function __construct()
	{
		parent::__construct();
		
		// Tai cac file thanh phan
		$this->load->model('admin_group_model');
		$this->lang->load('admin/admin');
	}
	
	/**
	 * Remap method
	 */
	function _remap($method)
	{
		if (in_array($method, array('edit', 'del')))
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
		$rules['name'] 			= array('name', 'required|trim|xss_clean');
		$rules['level'] 			= array('level', 'required|trim|xss_clean|callback__check_level');
		//$rules['permissions'] 			= array('permissions', 'required|trim|xss_clean|');
		$rules['sort_order'] 	= array('sort_order', 'is_natural');
		
		$this->form_validation->set_rules_params($params, $rules);
	}
	public function _check_level($value){
			$admin =admin_get_account_info();

			if($value >$admin->level )// khong co quyen tao, sua nhom >= quyen han cua minh
			{
				$this->form_validation->set_message(__FUNCTION__, lang('notice_value_invalid'));
				return FALSE;
			}
			else{
				if($value == $admin->level ) {
					$act = $this->_get_act();
					$id = $this->uri->rsegment(3);
					if ($act == 'edit' && $id) {
						if ($admin->group_id != $id) // neu khong phai sua group minh
						{
							$this->form_validation->set_message(__FUNCTION__, lang('notice_value_invalid'));
							return FALSE;
						}
					}
				}

			}


		return TRUE;

	}
	/**
	 * Lay gia tri cua permissions
	 */
	function _get_permissions()
	{
		// Lay config & input
		$config = admin_permission_list();
		$input 	= $this->input->post('permissions');
		
		// Xu ly gia tri
		$permissions = array();
		if($input)
		foreach ($input as $c => $ps)
		{
			// Kiem tra controller
			if ( ! isset($config[$c]))
			{
				continue;
			}
			
			// Kiem tra permission
			$_ps = array();
			foreach ($ps as $p)
			{
				if (isset($config[$c][$p]))
				{
					$_ps[] = $p;
				}
			}
			
			// Luu vao $permissions
			if (count($_ps))
			{
				$permissions[$c] = $_ps;
			}
		}
		
		return $permissions;
	}
	
	/**
	 * Tao thong tin permissions
	 */
	function _create_permissions_info()
	{
		$this->data['permissions_groups'] = config('menu', 'widget/admin');

		//pr($this->data['permissions_groups'],0);
		// Lay danh sach permissions
		$permissions = admin_permission_list();
		$this->data['permissions'] = $permissions;
		//pr($permissions);
		// Lay danh sach controller name
		$this->load->model('module_model');
		
		$controllers_name = array();
		foreach ($permissions as $c => $ps)
		{
			$n = '';
			$match = '';
			if (preg_match('#^md-(.+)$#', $c, $match))
			{
				$module = $match[1];
				$module = $this->module_model->get($module, 'name');
				$n = $module->name;
			}
			else 
			{
				$n = lang('mod_'.$c);
			}
			
			$controllers_name[$c] = $n;
		}
		$this->data['controllers_name'] = $controllers_name;
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
	 * Them moi
	 */
	function add()
	{
		// Tai cac file thanh phan
		$this->load->helper('form');
		$this->load->library('form_validation');
		
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
			$params = array('name','level', 'sort_order', 'permissions');
			$this->_set_rules($params);
			
			// Xu ly du lieu
			$result = array();
			if ($this->form_validation->run())
			{
				// Lay input
				$permissions = $this->_get_permissions();
				$permissions = serialize($permissions);
				
				// Them du lieu vao data
				$data = array();	
				$data['name']			= $this->input->post('name');
				$data['level']		= $this->input->post('level');
				$data['sort_order']		= $this->input->post('sort_order');
				$data['permissions']	= $permissions;
				$this->admin_group_model->create($data);
				
				// Khai bao du lieu tra ve
				$result['complete'] = TRUE;
				$result['location'] = admin_url('admin_group');
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
		
		
		// Tao thong tin permissions
		$this->_create_permissions_info();
		
		// Luu bien gui den view
		$this->data['info'] = null;
		$this->data['action'] = current_url();
		$this->data['permissions'] = admin_permission_list();
		$this->data['levels'] = range(1,10,1);
		// Tao cay thu muc
		$breadcrumbs = array();
		$breadcrumbs[] = array('', lang('group_account'));
		$breadcrumbs[] = array(admin_url('admin_group'), lang('mod_admin_group'));
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
			$params = array('name','level', 'sort_order', 'permissions');
			$this->_set_rules($params);
			
			// Xu ly du lieu
			$result = array();
			if ($this->form_validation->run())
			{
				// Lay input
				$permissions = $this->_get_permissions();
				$permissions = serialize($permissions);
				
				// Cap nhat du lieu vao data
				$data = array();	
				$data['name']			= $this->input->post('name');
				$data['level']		= $this->input->post('level');
				$data['sort_order']		= $this->input->post('sort_order');
				$data['permissions']	= $permissions;
				$this->admin_group_model->update($info->id, $data);
				
				// Khai bao du lieu tra ve
				$result['complete'] = TRUE;
				$result['location'] = admin_url('admin_group');
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
		
		
		// Xu ly thong tin
		$info->permissions = @unserialize($info->permissions);
		$this->data['info'] = $info;
		$this->data['levels'] = range(1,10,1);
		// Tao thong tin permissions
		$this->_create_permissions_info();
		
		// Luu bien gui den view
		$this->data['action'] = current_url();
		
		// Tao cay thu muc
		$breadcrumbs = array();
		$breadcrumbs[] = array('', lang('group_account'));
		$breadcrumbs[] = array(admin_url('admin_group'), lang('mod_admin_group'));
		$breadcrumbs[] = array(current_url(), lang('edit'));
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
		$this->admin_group_model->del($info->id);
		
		// Gui thong bao
		set_message(lang('notice_del_success'));
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
			$info = $this->admin_group_model->get_info($id);
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

		if($admin->group_id != $info->id){
			//pr($admin);
			if($admin->level <= $info->level )
				return false;
		}

		switch ($action)
		{
			case 'edit':{
				if( $info->id == '1'){
					return FALSE;
				}
				return TRUE;
			}
			case 'del':
			{
				if( $info->id == '1'){
					return FALSE;
				}
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
		// Lay danh sach
		$list = $this->admin_group_model->get_list();
		
		$actions = array('edit', 'del');
		$list = admin_url_create_option($list, strtolower(__CLASS__), 'id', $actions);
		foreach ($list as $row)
		{
			foreach ($actions as $action)
			{
				$row->{'_can_'.$action} = ($this->_can_do($row, $action) && admin_permission_url($row->{'_url_'.$action})) ? TRUE : FALSE;
			}
		}
		$this->data['list'] = $list;
		
		$this->_display();
	}
	
}