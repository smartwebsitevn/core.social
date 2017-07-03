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
		$this->lang->load('site/contact');
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
		$rules = array();
		$rules['name'] 				= array('name', 'required|trim|xss_clean');
		$rules['email'] 			= array('email', 'required|trim|xss_clean|valid_email');
		$type =t('input')->post('type') ;
		if($type){
			$rules['phone'] 				= array('name', 'required|trim|xss_clean|filter_html');
			$rules['address'] 			= array('subject', 'required|trim|xss_clean|min_length[6]|max_length[255]|filter_html');
		}else{
			$rules['phone'] 				= array('name', 'trim|xss_clean|filter_html');
			$rules['address'] 			= array('subject', 'trim|xss_clean|min_length[6]|max_length[255]|filter_html');

		}
		$rules['subject'] 			= array('subject', 'required|trim|xss_clean|min_length[6]|max_length[255]|filter_html');
		$rules['message'] 			= array('message', 'required|trim|xss_clean|min_length[6]|max_length['.(1024*512).']|filter_html');
		$rules['security_code'] 	= array('security_code', 'required|trim|callback__check_security_code');
		
		$this->form_validation->set_rules_params($params, $rules);
	}
	
	/**
	 * Kiem tra ma bao mat
	 */
	function _check_security_code($value)
	{
		$this->load->library('captcha_library');
		
		if ( ! $this->captcha_library->check($value, 'four'))
		{
			$this->form_validation->set_message(__FUNCTION__, lang('notice_value_incorrect'));
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
 *  Main handle
 * ------------------------------------------------------
 */
	/**
	 * Home
	 */
	function index()
	{
		// Tai cac file thanh phan
		$this->load->helper('form');
		$this->load->library('form_validation');
		
		// Xu ly form
		if ($this->input->post('_submit'))
		{
			// Gan dieu kien cho cac bien
			$params = array('name',  'email',  'message');
			$type =t('input')->post('type',1) ;
			if($type == 'regiter'){
				array_push($params, 'address');
				array_push($params, 'phone');
			}
			if($type == 'order'){
				array_push($params, 'phone');
			}
			else{
				array_push($params, 'subject');
				array_push($params, 'security_code');
			}

			/*if (t('input')->post('phone') !== null) {
				array_push($params, 'phone');
			}
			if (t('input')->post('address') !== null) {
				array_push($params, 'address');
			}
			if (t('input')->post('subject') !== null) {
				array_push($params, 'subject');
			}
			if (t('input')->post('security_code') !== null) {
				array_push($params, 'security_code');
			}*/
			$this->_set_rules($params);
			
			// Xu ly du lieu
			$result = array();
			if ($this->form_validation->run())
			{
				// Reset value
				lib('captcha')->del();

				// Cap nhat vao data
				$data = array();
				if($type)
				$data['type']		= $this->_mod()->config("contact_type_".$type);
				$data['name']		= strip_tags($this->input->post('name',1));
				$data['email']		= $this->input->post('email',1);
				$data['message']	= strip_tags($this->input->post('message',1));
				$data['created'] 	= now();
				if($type == 'register') {
					$data['phone']		= $this->input->post('phone',1);
					$data['address']		= $this->input->post('email',1);
					$data['subject'] = "Đăng ký nhận tin miễn phí";
					$url=site_url();
				}
				elseif($type == 'order') {
					$data['phone']		= $this->input->post('phone',1);
					$data['subject'] = "Đặt hàng";
					$url=site_url();
				}
				else{
					$data['subject'] = strip_tags($this->input->post('subject', 1));
					$url=current_url();

				}

				$this->_model()->create($data);
				
				// Khai bao du lieu tra ve
				$result['complete'] = TRUE;
				$result['location'] = $url;
				set_message(lang('notice_send_contact_success'));
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
		
		
		// Lay thong tin client
		$client = array();
		$client = set_default_value($client, array('email', 'name'));
		$user = user_get_account_info();
		if ($user)
		{
			$client['email'] 	= $user->email;
			$client['name'] 	= $user->name;
		}
		$this->data['client'] = $client;
		
		// Luu cac bien gui den view
		$this->data['action']	= current_url();
		$this->data['captcha'] 	= site_url('captcha/four');
		
		// Breadcrumbs
		page_info('breadcrumbs', array(current_url(), lang('title_contact')));
		
		// Gan thong tin page
		page_info('title', lang('title_contact'));
		
		// Hien thi view
		$this->_display();
	}
	
}
