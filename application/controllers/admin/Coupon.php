<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Coupon extends MY_Controller {
	
	function __construct()
	{
		parent::__construct();
		
		// Tai cac file thanh phan
		$this->load->model('coupon_model');
		$this->lang->load('admin/coupon');
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
		$rules['discount'] 			= array('discount', 'required|trim|xss_clean');
		$rules['discount_type']     = array('discount_type', 'required|callback__check_discount_type');
		$rules['number_user'] 		= array('number_user', 'required|trim|xss_clean|is_natural_no_zero');
		$rules['expire'] 		    = array('expire', 'required|trim|xss_clean');
		
		$this->form_validation->set_rules_params($params, $rules);
	}
	
	
     /**
	 * Kiem tra nhom ho tro
	 */
	function _check_discount_type($value)
	{
		if (!$value)
		{
			return TRUE;
		}
		
		$discount_types = config('discount_types', 'main');
		if (!isset($discount_types[$value]))
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
		
		//tao ma giam gia
		$code = now().$this->_create_pincode();
	    $this->data['code'] = $code;
	    
		// Xu ly form
		if ($this->input->post('_submit'))
		{
			// Gan dieu kien cho cac bien
			$params = array('name', 'discount_type', 'discount', 'number_user', 'expire');
			$this->_set_rules($params);
			
			// Xu ly du lieu
			$result = array();
			if ($this->form_validation->run())
			{
				$expire = $this->input->post('expire');
				$status = ($this->input->post('status')) ? config('status_on', 'main') : config('status_off', 'main');
				
				// Cap nhat vao data
				$data = array();	
				$data['code']  	        = $code;
				$data['name']			= $this->input->post('name');
				$data['discount_type']	= $this->input->post('discount_type');
				$data['discount']		= $this->input->post('discount');
				$data['number_user']  	= $this->input->post('number_user');
				$data['expire']  	    = get_time_from_date($expire);
				$data['status']  	    = $status;
				
				$this->coupon_model->create($data);
				
				// Khai bao ket qua tra ve
				$result['complete'] = TRUE;
				$result['location'] = admin_url('coupon');
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
		$discount_types = config('discount_types', 'main');
		$this->data['discount_types'] = $discount_types;
		
		$this->data['action'] = current_url();
		
		// Tao cay thu muc
		$breadcrumbs = array();
		$breadcrumbs[] = array('', lang('group_tran'));
		$breadcrumbs[] = array(admin_url('coupon'), lang('mod_coupon'));
		$breadcrumbs[] = array('', lang('add'));
		$this->data['breadcrumbs'] = $breadcrumbs;
		
		// Hien thi view
		$this->data['temp'] = 'admin/coupon/add';
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
			$params = array('name','discount_type','discount', 'number_user', 'expire');
			$this->_set_rules($params);
			
			// Xu ly du lieu
			$result = array();
			if ($this->form_validation->run())
			{
				$expire = $this->input->post('expire');
				$status = ($this->input->post('status')) ? config('status_on', 'main') : config('status_off', 'main');
				
				// Cap nhat vao data
				$data = array();	
				$data['name']			= $this->input->post('name');
				$data['discount_type']	= $this->input->post('discount_type');
				$data['discount']		= $this->input->post('discount');
				$data['number_user']  	= $this->input->post('number_user');
				$data['expire']  	    = get_time_from_date($expire);
				$data['status']  	    = $status;
				$this->coupon_model->update($info->id, $data);
				
				// Khai bao ket qua tra ve
				$result['complete'] = TRUE;
				$result['location'] = admin_url('coupon');
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
		$discount_types = config('discount_types', 'main');
		$this->data['discount_types'] = $discount_types;
		
		$this->data['action'] = current_url();
		$info->expire = get_date($info->expire );
		$this->data['info'] = $info;

		// Tao cay thu muc
		$breadcrumbs = array();
		$breadcrumbs[] = array('', lang('group_tran'));
		$breadcrumbs[] = array(admin_url('coupon'), lang('mod_coupon'));
		$breadcrumbs[] = array('', lang('edit'));
		$this->data['breadcrumbs'] = $breadcrumbs;
		
		// Hien thi view
		$this->data['temp'] = 'admin/coupon/edit';
		$this->load->view('admin/main', $this->data);
	}

	
	/**
	 * Xoa du lieu
	 */
	function _del($info)
	{
		
		// Thuc hien xoa
		$this->coupon_model->del($info->id);
		
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
		$info = $this->coupon_model->get_info($id);
		if (!$info)
		{
			$this->session->set_flashdata('flash_message', array('warning', lang('notice_page_not_found')));
			redirect_admin('coupon');
		}
		
		// Chuyen den ham duoc yeu cau
		$this->{'_'.$action}($info);
	}
	
	/**
	 * Tao pincode ngau nhien
	*/
	private function _create_pincode()
	{
		$pincode = "";
		for ($i = 0; $i <= 5; $i++)
		{
			$pincode .= rand(0, 9);
		}
		
		return $pincode;
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
		$list = $this->coupon_model->get_list($input);
		$discount_types = config('discount_types', 'main');
		$status = config('status', 'main');
		$status = $status['_types'];
		foreach ($list as $row)
		{
		    $row->_expire   = get_date($row->expire);
		    $row->_discount = number_format($row->discount).' '.lang('discount_type_'.$discount_types[$row->discount_type]);
		    $row->_status   = lang('status_'.$status[$row->status]);
		}
		
		$list = admin_url_create_option($list, 'coupon/action', 'id', array('edit', 'del'));
		
		$this->data['list'] = $list;
		
		
		
		// Breadcrumbs
		$breadcrumbs = array();
		$breadcrumbs[] = array('', lang('group_tran'));
		$breadcrumbs[] = array(admin_url('coupon'), lang('mod_coupon'));
		$breadcrumbs[] = array('', lang('list'));
		$this->data['breadcrumbs'] = $breadcrumbs;
		
		// Message
		$message = $this->session->flashdata('flash_message');
		if ($message)
		{
			$this->data['message'] = $message;
		}
		
		// Hien thi view
		$this->data['temp'] = 'admin/coupon/index';
		$this->load->view('admin/main', $this->data);
	}
	
}