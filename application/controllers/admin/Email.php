<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Email extends MY_Controller {
	
	/**
	 * Ham khoi dong
	 */
	function __construct()
	{
		parent::__construct();
		
		// Tai cac file thanh phan
		$this->load->model('email_model');
		$this->lang->load('admin/email');
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
		$rules['key'] 		= array('key', 'required|trim|xss_clean|alpha_dash|callback__check_key');
		$rules['title'] 	= array('title', 'required|trim|xss_clean');
		$rules['content'] 	= array('content', 'required|trim|xss_clean');
		
		$this->form_validation->set_rules_params($params, $rules);
	}
	
	/**
	 * Kiem tra key
	 */
	function _check_key($value)
	{
		$info = $this->email_model->get_info($value, 'key');
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
			$params = array('key', 'title', 'content');
			$this->_set_rules($params);
			
			// Xu ly du lieu
			$result = array();
			if ($this->form_validation->run())
			{
				// Cap nhat vao data
				$this->email_model->set(
					$this->input->post('key'),
					$this->input->post('title'),
					$this->input->post('content')
				);
				
				// Khai bao du lieu tra ve
				$result['complete'] = TRUE;
				$result['location'] = admin_url('email');
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
		
		
		// Luu bien gui den view
		$this->data['action'] = current_url();
		
		// Tao cay thu muc
		$breadcrumbs = array();
		$breadcrumbs[] = array('', lang('group_system'));
		$breadcrumbs[] = array(admin_url('email'), lang('mod_email'));
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
			$params = array('title', 'content');
			$this->_set_rules($params);
			
			// Xu ly du lieu
			$result = array();
			if ($this->form_validation->run())
			{
				// Cap nhat vao data
				$this->email_model->set(
					$info->key,
					$this->input->post('title'),
					$this->input->post('content')
				);
				
				// Khai bao du lieu tra ve
				$result['complete'] = TRUE;
				$result['location'] = admin_url('email');
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
		
		
		// Luu bien gui den view
		$this->data['action'] = current_url();
		$this->data['info'] = $info;
		
		// Tao cay thu muc
		$breadcrumbs = array();
		$breadcrumbs[] = array('', lang('group_system'));
		$breadcrumbs[] = array(admin_url('email'), lang('mod_email'));
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
		$this->email_model->del($info->key);
		
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
			// Kiem tra id
			$info = $this->email_model->get_info($id);
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
			case 'del':
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
		// Lay danh sach
		$list = $this->email_model->get_list();
		
		$actions = array('edit', 'del');
		$list = admin_url_create_option($list, strtolower(__CLASS__), 'key', $actions);
		foreach ($list as $row)
		{
			foreach ($actions as $action)
			{
				$row->{'_can_'.$action} = ($this->_can_do($row, $action) && admin_permission_url($row->{'_url_'.$action})) ? TRUE : FALSE;
			}
		}
		$this->data['list'] = $list;
		
		// Tao cay thu muc
		$breadcrumbs = array();
		$breadcrumbs[] = array('', lang('group_system'));
		$breadcrumbs[] = array(admin_url('email'), lang('mod_email'));
		$breadcrumbs[] = array(current_url(), lang('list'));
		$this->data['breadcrumbs'] = $breadcrumbs;
		
		// Hien thi view
		$this->_display();
	}
	
}