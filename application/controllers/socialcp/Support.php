<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Support extends MY_Controller {
	
	function __construct()
	{
		parent::__construct();
		
		// Tai cac file thanh phan
		$this->load->model('support_model');
		$this->load->model('support_group_model');
		$this->lang->load('admin/support');
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
		$rules['name'] 				= array('name', 'required|trim|xss_clean');
		$rules['phone'] 			= array('phone', 'required|trim|xss_clean');
		$rules['group_id'] 		    = array('group_support', 'required|callback__check_group_id');
		$this->form_validation->set_rules_params($params, $rules);
	}
	
	
     /**
	 * Kiem tra nhom ho tro
	 */
	function _check_group_id($value)
	{
		if (!$value)
		{
			return TRUE;
		}
		
		$where = array();
		$where['id'] = $value;
		$id = $this->support_group_model->get_id($where);

		if (!$id)
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
			$params = array('name','group_id','phone');
			$this->_set_rules($params);
			
			// Xu ly du lieu
			$result = array();
			if ($this->form_validation->run())
			{
				// Cap nhat vao data
				$data = array();	
				$data['name']			= $this->input->post('name');
				$data['group_id']		= $this->input->post('group_id');
				$data['phone']			= $this->input->post('phone');
				//$data['yahoo_image']    = $this->input->post('yahoo_image');
				$data['yahoo']  	    = $this->input->post('yahoo');
				$data['gmail']  	    = $this->input->post('gmail');
				$data['skype']		    = $this->input->post('skype');
				$data['sort_order']	    = $this->input->post('sort_order');
				
				$this->support_model->create($data);
				
				// Khai bao ket qua tra ve
				$result['complete'] = TRUE;
				$result['location'] = admin_url('support');
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
		
		
		// Luu bien gui den view
		$this->data['action'] = current_url();
		
		//danh sach nhom ho tro truc tuyen
		$input = array();
		$input['order'] = array('sort_order', 'asc');
		$groups = $this->support_group_model->get_list($input);
		$this->data['groups'] = $groups;
		
		// Tao cay thu muc
		$breadcrumbs = array();
		$breadcrumbs[] = array('', lang('group_support'));
		$breadcrumbs[] = array(admin_url('support'), lang('mod_support'));
		$breadcrumbs[] = array('', lang('add'));
		$this->data['breadcrumbs'] = $breadcrumbs;
		
		// Hien thi view
		$this->data['temp'] = 'admin/support/add';
		$this->load->view('admin/main', $this->data);
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
			$params = array('name','group_id','phone');
			$this->_set_rules($params);
			
			// Xu ly du lieu
			$result = array();
			if ($this->form_validation->run())
			{
				// Cap nhat vao data
				$data = array();	
				$data['name']			= $this->input->post('name');
				$data['group_id']		= $this->input->post('group_id');
				$data['phone']			= $this->input->post('phone');
				//$data['yahoo_image']    = $this->input->post('yahoo_image');
				$data['yahoo']  	    = $this->input->post('yahoo');
				$data['gmail']  	    = $this->input->post('gmail');
				$data['skype']		    = $this->input->post('skype');
				$data['sort_order']	    = $this->input->post('sort_order');
				
				$this->support_model->update($info->id, $data);
				
				// Khai bao ket qua tra ve
				$result['complete'] = TRUE;
				$result['location'] = admin_url('support');
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

		// Luu bien gui den view
		$this->data['action'] = current_url();
		$this->data['info'] = $info;
		
		//danh sach nhom ho tro truc tuyen
		$input = array();
		$input['order'] = array('sort_order', 'asc');
		$groups = $this->support_group_model->get_list($input);
		$this->data['groups'] = $groups;
		
		// Tao cay thu muc
		$breadcrumbs = array();
		$breadcrumbs[] = array('', lang('group_support'));
		$breadcrumbs[] = array(admin_url('support'), lang('mod_support'));
		$breadcrumbs[] = array('', lang('edit'));
		$this->data['breadcrumbs'] = $breadcrumbs;
		
		// Hien thi view
		$this->data['temp'] = 'admin/support/edit';
		$this->load->view('admin/main', $this->data);
	}

	
	/**
	 * Xoa du lieu
	 */
	function _del($info)
	{
		
		// Thuc hien xoa
		$this->support_model->del($info->id);
		
		// Gui thong bao
		$this->session->set_flashdata('flash_message', array('success', lang('notice_del_success')));
		return TRUE;
	}
	
	/**
	 * Thuc hien tuy chinh
	 */
	function action()
	{
		// Lay input
		$action = $this->uri->rsegment(3);
		$id = $this->uri->rsegment(4);
		$id = (!is_numeric($id)) ? 0 : $id;
		
		// Kiem tra id
		$info = $this->support_model->get_info($id);
		if (!$info)
		{
			$this->session->set_flashdata('flash_message', array('warning', lang('notice_page_not_found')));
			redirect_admin('support');
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
		$list = $this->support_model->get_list($input);
		$list = admin_url_create_option($list, 'support/action', 'id', array('edit', 'del'));
		
		$this->data['list'] = $list;
		
		// Breadcrumbs
		$breadcrumbs = array();
		$breadcrumbs[] = array('', lang('group_support'));
		$breadcrumbs[] = array(admin_url('support'), lang('mod_support'));
		$breadcrumbs[] = array('', lang('list'));
		$this->data['breadcrumbs'] = $breadcrumbs;
		
		// Message
		$message = $this->session->flashdata('flash_message');
		if ($message)
		{
			$this->data['message'] = $message;
		}
		
		// Hien thi view
		$this->data['temp'] = 'admin/support/index';
		$this->load->view('admin/main', $this->data);
	}
	
}