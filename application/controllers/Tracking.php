<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class tracking extends MY_Controller
{
	/**
	 * Ham khoi dong
	 */
	public function __construct()
	{
		parent::__construct();
		
		// Tai cac file thanh phan
		$this->lang->load('site/'.__CLASS__);
		$this->lang->load(__CLASS__);
		$this->data['tracking_status'] = $this->_mod()->config('status');
		$this->data['tracking_vehicle'] = $this->_mod()->config('vehicle');
		$this->data['class'] = __CLASS__;
	}
	
	/**
	 * Danh sach
	 */
	public function index()
	{
		if ($this->data['data'] = $this->input->post())
		{
			$this->load->library('form_validation');
			// Gan dieu kien cho cac bien
			$params = array('no', 'security_code');
			$this->_set_rules($params);

			// Xu ly du lieu
			$result = array();
			$result['status'] = false;
			if ($this->form_validation->run())
			{
				// Reset value
				$this->captcha_library->del('three');

				$where = array();
				$where['where']['no'] = $this->input->post('no');
				$tracking = $this->_model()->read($where);
				$this->data['row'] = $this->_mod()->add_info($tracking);
				$result['status'] = true;
				$result['content'] = view('tpl::tracking/view', $this->data, true);
			}else {
				$result['label'] = validation_errors();
			}
			return_json($result);
		}

		$this->data['action']	= current_url();
		$this->data['captcha'] 	= site_url('captcha/three');
		page_info('title', lang('tracking_title'));
		$this->data['breadcrumbs'] = array(current_url(), lang(__CLASS__), lang(__CLASS__));
		$this->_display();
	}


	function _set_rules($params)
	{
		$rules = array();
		$rules['security_code'] 	= array('security_code', 'required|trim|callback__check_security_code');
		$rules['no'] 				= array('no', 'required|trim|xss_clean|callback__check_no');
		$this->form_validation->set_rules_params($params, $rules);
	}

	/**
	 * Kiem tra ma bao mat
	 */
	function _check_no($value)
	{

		$where['where']['no'] = $value;
		if ( ! $this->_model()->total($where))
		{
			$this->form_validation->set_message(__FUNCTION__, lang('notice_no_invalid'));
			return FALSE;
		}

		return TRUE;
	}

	function _check_security_code($value)
	{
		$this->load->library('captcha_library');

		if ( ! $this->captcha_library->check($value, 'three'))
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
}