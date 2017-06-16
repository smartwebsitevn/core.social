<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Support_group extends MY_Controller {
	
	function __construct()
	{
		parent::__construct();
		
		// Tai cac file thanh phan
		$this->load->model('support_group_model');
		$this->lang->load('admin/support_group');
	}
	
	
/*
 * ------------------------------------------------------
 *  Rules params
 * ------------------------------------------------------
 */
	/**
	 * Gan dieu kien cho cac bien
	 */
	function _set_rules($params)
	{
		$rules 						= array();
		$rules['name'] 				= array('name', 'required|trim|xss_clean');
		$rules['sort_order'] 		= array('sort_order', 'is_natural|less_than[128]');
		
		$this->form_validation->set_rules_params($params, $rules);
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
			$params = array('name', 'sort_order');
			$this->_set_rules($params);
			
			// Xu ly du lieu
			$result = array();
			if ($this->form_validation->run())
			{
				// Them du lieu vao support_group
				$data = array();	
				$data['name']		= $this->input->post('name');
				$data['sort_order']	= $this->input->post('sort_order');
				$this->support_group_model->create($data);
				
				// Khai bao ket qua tra ve
				$result['complete'] = TRUE;
				$result['location'] = admin_url('support_group');
				$this->session->set_flashdata('flash_message', array('success', lang('notice_add_success')));
			}
			else
			{
				foreach ($params as $param)
				{
					$result[$param] = form_error($param);
				}
			}
			
			$output = json_encode($result);
			set_output('json', $output);
		}

		
		// Luu cac bien gui den view
		$this->data['action'] = current_url();
		
		// Hien thi view
		$this->load->view('admin/support_group/add', $this->data);
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
			$params = array('name', 'sort_order');
			$this->_set_rules($params);
			
			// Xu ly du lieu
			$result = array();
			if ($this->form_validation->run())
			{
				// Cap nhat du lieu vao support_group
				$data = array();
				$data['name']		= $this->input->post('name');
				$data['sort_order']	= $this->input->post('sort_order');
				$this->support_group_model->update($info->id, $data);
				
				// Khai bao ket qua tra ve
				$result['complete'] = TRUE;
				$result['location'] = admin_url('support_group');
				$this->session->set_flashdata('flash_message', array('success', lang('notice_update_success')));
			}
			else
			{
				foreach ($params as $param)
				{
					$result[$param] = form_error($param);
				}
			}
			
			$output = json_encode($result);
			set_output('json', $output);
		}
		
		
		// Luu cac bien gui den view
		$this->data['info'] = $info;
		$this->data['action'] = current_url();
		
		// Hien thi view
		$this->load->view('admin/support_group/edit', $this->data);
	}
	
	/**
	 * Xoa du lieu
	 */
	function _del($info)
	{
		//kiem tra xem trong nhom nay co ho tro chua
		$this->load->model('support_model');
		$input = $support = array();
		$input['where'] = array('group_id' => $info->id);
		$support = $this->support_model->get_list($input);
		
		if(!empty($support))
		{
			// Gui thong bao
			$this->session->set_flashdata('flash_message', array('warning', lang('notice_delete_support_to_group')));
			return FALSE;
		}
		
		// Xoa trong support_group
		$this->support_group_model->del($info->id);
		
		// Gui thong bao
		$this->session->set_flashdata('flash_message', array('success', lang('notice_del_success')));
		return TRUE;
	}
	
	/**
	 * Thuc hien tuy chinh voi support_group
	 */
	function action()
	{
		// Lay input
		$action = $this->uri->rsegment(3);
		$id = $this->uri->rsegment(4);
		$id = (!is_numeric($id)) ? 0 : $id;
		
		// Kiem tra id
		$info = $this->support_group_model->get_info($id);
		if (!$info)
		{
			$this->session->set_flashdata('flash_message', array('warning', lang('notice_page_not_found')));
			redirect_admin('support_group');
		}
		
		// Chuyen den ham duoc yeu cau
		$this->{'_'.$action}($info);
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
		$input = array();
		$input['order'] = array('sort_order', 'asc');
		
		$list = $this->support_group_model->get_list($input);
		$list = admin_url_create_option($list, 'support_group/action', 'id', array('edit', 'del'));
		$this->data['list'] = $list;
		
		// Breadcrumbs
		$breadcrumbs = array();
		$breadcrumbs[] = array(admin_url('support_group'), lang('mod_support_group'));
		$breadcrumbs[] = array('', lang('list'));
		$this->data['breadcrumbs'] = $breadcrumbs;
		
		// Message
		$message = $this->session->flashdata('flash_message');
		if ($message)
		{
			$this->data['message'] = $message;
		}
		
		// Hien thi view
		$this->data['temp'] = 'admin/support_group/index';
		$this->load->view('admin/main', $this->data);
	}
	
}