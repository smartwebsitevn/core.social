<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends MY_Controller
{

	/**
	 * Trang chinh
	 */
	public function index()
	{

		$this->_display( 'index' );
	}

	/**
	 * Thay doi ngon ngu
	 */
	public function lang()
	{
		// Lay currency_id
		$id = $this->uri->rsegment(3);
		$id = ( ! is_numeric($id)) ? 0 : $id;
		if(!$id) return;

		//pr($id);
		// Thuc hien thay doi
		if ( ! lang_change($id))
		{
			set_message(lang('notice_can_not_handle'));
		}

		// Luu cookie xac nhan lang da duoc cap nhat
		set_cookie('lang_update', TRUE, config('cookie_expire', 'main'));

		$url = $this->input->get("url");
		if(!$url)
			$url =site_url();
		$this->_response(["location"=>$url]);
		//redirect($url);
	}

	/**
	 * Thay doi tien te hien thi
	 */
	public function currency()
	{
		// Lay currency_id
		$id = $this->uri->rsegment(3);
		$id = ( ! is_numeric($id)) ? 0 : $id;

		// Thuc hien thay doi
		if ( ! currency_change($id))
		{
			set_message(lang('notice_can_not_handle'));
		}
	}

	/**
	 * Gan thiet bi dang su dung
	 */
	public function device()
	{
		$value = $this->uri->rsegment(3);

		if (in_array($value, array('desktop', 'mobile')))
		{
			t('session')->set_userdata('device', $value);
		}
		else
		{
			t('session')->unset_userdata('device');
		}

		redirect();
	}
	function email_register_ (){
		$this->lang->load('site/email_register');
		$this->load->helper('email');
		$result = array('email' => lang('please_email'));
		$email = $this->input->post('email');
		if(!$email || !valid_email($email)){
			$this->_form_submit_output($result);
		}

		$result = array('complete' => true,'location'=>'');

		// kiem tra email co trong csdl hay chua
		$where = array();
		$where['where']['email'] = $email;
		if($info = model('email_register')->read($where)){
			// huy trang thai cua email
			$data = array();
			if($info->status == 1){
				set_message(lang('unset_email_success'),'success');
				$data['status'] = 0;
			}
			else {
				set_message(lang('set_email_success'),'success');
				$data['status'] = 1;
			}
			// cap nhap trang thai email
			model('email_register')->update($info->id, $data);
			$this->_form_submit_output($result);
		}

		// them email vao csdl
		$data = array();
		//$data['status'] = 1;
		$data['ip'] = $this->input->ip_address();
		$data['email'] = $email;
		$data['created'] = now();
		model('email_register')->create($data);

		set_message(lang('register_email_success'),'success');
		$this->_form_submit_output($result);
	}

	function email_register()
	{
		// Tai cac file thanh phan
		$this->lang->load('site/email_register');
		$this->load->helper('email');
		$this->load->helper('form');
		$this->load->library('form_validation');

		// Xu ly form
		if ($this->input->post('_submit'))
		{
			// Gan dieu kien cho cac bien
			$params = array( 'email');
			$this->_set_rules($params);

			// Xu ly du lieu
			$result = array();
			if ($this->form_validation->run())
			{

				// them email vao csdl
				$data = array();
				//$data['status'] = 1;
				$data['ip'] = $this->input->ip_address();
				$data['email'] = $this->input->post('email',1);;
				$data['created'] = now();
				model('email_register')->create($data);

				// Khai bao du lieu tra ve
				$result['complete'] = TRUE;
				$result['reload'] = 1;
				set_message(lang('notice_email_register_success'));
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
		$rules['email'] 			= array('email', 'required|trim|xss_clean|valid_email|max_length[30]|callback__check_email');
		$rules['security_code'] 	= array('security_code', 'required|trim|callback__check_security_code');

		$this->form_validation->set_rules_params($params, $rules);
	}
	/**
	 * Kiem tra email nay co ton tai hay khong
	 */
	public function _check_email($value)
	{
		if (model('email_register')->get_id(array('email' => $value))) {
			$this->form_validation->set_message(__FUNCTION__, lang('notice_email_registered'));
			return FALSE;
		}

		return TRUE;
	}

}
