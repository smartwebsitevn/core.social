<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Menu extends MY_Controller {
	
	/**
	 * Ham khoi dong
	 */
	function __construct()
	{
		parent::__construct();
		
		// Tai cac file thanh phan
		$this->load->model('menu_model');
		$this->lang->load('admin/menu');
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
 *  Menu handle
 * ------------------------------------------------------
 */
	/**
	 * Gan dieu kien cho cac bien
	 */
	function _set_rules($params = array())
	{
		$rules = array();
		$rules['key'] 		= array('key', 'required|trim|alpha_dash|callback__check_key');
		$rules['name'] 		= array('name', 'required|trim|xss_clean');
		
		$this->form_validation->set_rules_params($params, $rules);
	}
	
	/**
	 * Kiem tra key
	 */
	function _check_key($value)
	{
		$info = $this->menu_model->get($value, 'key');
		if ($info)
		{
			$this->form_validation->set_message(__FUNCTION__, lang('notice_already_used'));
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
		$result['accept'] 	= $this->form_validation->run();
		$result['error'] 	= form_error($param);
		
		$output = json_encode($result);
		set_output('json', $output);
	}
	
	// --------------------------------------------------------------------
	
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
			$params = array('key', 'name');
			$this->_set_rules($params);
			
			// Xu ly du lieu
			$result = array();
			if ($this->form_validation->run())
			{
				// Lay input

				$data = array();
				$data['name'] = $this->input->post('name');
				$data['key'] = $this->input->post('key');
				$data['sort_order'] = $this->input->post('sort_order');
				$this->menu_model->create($data);

				// Khai bao du lieu tra ve
				$result['complete'] = TRUE;
				$result['location'] = admin_url('menu');
				set_message(lang('notice_add_success'));
			}
			
			// Neu du lieu khong phu hop
			if (empty($result['complete']))
			{
				foreach ($params as $param)
				{
					$result[$param] = form_error($param);
				}
			}
			
			// Form output
			$this->_form_submit_output($result);
		}
		
		
		// Luu cac bien gui den view
		$this->data['action'] = current_url();
		
		// Hien thi view
		$this->_display('form');
	}

	/**
	 * Chinh sua
	 */
	function _edit($info)
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
			$params = array('name');
			$this->_set_rules($params);
			
			// Xu ly du lieu
			$result = array();
			if ($this->form_validation->run())
			{
				// Cap nhat vao data
				$data = array();
				$data['name'] = $this->input->post('name');
				$data['sort_order'] = $this->input->post('sort_order');
				$this->menu_model->update($info->id,$data);
				
				// Khai bao du lieu tra ve
				$result['complete'] = TRUE;
				$result['location'] = admin_url('menu');
				set_message(lang('notice_update_success'));
			}
			
			// Neu du lieu khong phu hop
			if (empty($result['complete']))
			{
				foreach ($params as $param)
				{
					$result[$param] = form_error($param);
				}
			}
			
			// Form output
			$this->_form_submit_output($result);
		}
		
		
		// Luu cac bien gui den view
		$this->data['info'] 	= $info;
		$this->data['action'] 	= current_url();
		
		// Hien thi view
		$this->_display('form');
	}
	
	/**
	 * Xoa du lieu
	 */
	function _del($info)
	{
		// Thuc hien xoa
		model('menu')->del($info->key);
		model('menu_item')->del_rule(['menu' => $info->key]);

		// Gui thong bao
		set_message(lang('notice_del_success'));
	}
	
	// --------------------------------------------------------------------
	
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
			// Kiem tra id
			$info = $this->menu_model->get_info($id);
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
			case 'edit':
			{
				return TRUE;
			}
			case 'del':
			{
				if($info->id !='1')
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
		// Cap nhat sort_order
		if ($this->input->get('act') == 'update_order')
		{
			$items = $this->input->post('items');
			$items = explode(',', $items);
			
			foreach ($items as $i => $id)
			{
				$data = array();
				$data['sort_order']	= $i;
				$this->menu_model->update($id, $data);
			}
			
			$output = json_encode(array('complete' => TRUE));
			set_output('json', $output);
		}
		
		
		// Lay danh sach menu
		$menus = $this->menu_model->get_list();
		
		$actions = array('edit', 'del');
		$menus = admin_url_create_option($menus, strtolower(__CLASS__), 'id', $actions);
		foreach ($menus as $menu)
		{
			// Menu action
			foreach ($actions as $action)
			{
				$menu->{'_can_'.$action} = ($this->_can_do($menu, $action) && admin_permission_url($menu->{'_url_'.$action})) ? TRUE : FALSE;
			}
		}
		$this->data['list'] = $menus;
		
		
		// Luu cac bien gui den view
		$this->data['menu']	 	= $this->input->get('menu');
		$this->data['url_update_order'] = current_url().'?act=update_order';
		
		// Breadcrumbs
		$breadcrumbs = array();
		$breadcrumbs[] = array(admin_url('menu'), lang('mod_menu'));
		$breadcrumbs[] = array(current_url(), lang('list'));
		$this->data['breadcrumbs'] = $breadcrumbs;
		
		// Hien thi view
		$this->_display();
	}
	
}