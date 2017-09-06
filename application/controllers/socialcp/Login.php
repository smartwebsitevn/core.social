<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends MY_Controller {
	
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
	 * Dang nhap
	 */
	function index()
	{
		// Tai cac file thanh phan
		$this->load->library('matrix_library');
		$this->load->library('form_validation');
		$this->load->helper('form');
		
		// Xu ly form
		if ($this->input->post('_submit'))
		{
			// Gan dieu kien cho cac bien
			$params = array('username', 'password', 'security_code');
			$this->form_validation->set_rules('username', 'lang:username', 'required|trim|xss_clean|alpha_dash');
			$this->form_validation->set_rules('password', 'lang:password', 'required|trim|xss_clean');
			$this->form_validation->set_rules('security_code', 'lang:security_code', 'required|trim|callback__check_security_code[four]');
			
			// Xu ly du lieu
			$result = array();
			if ($this->form_validation->run())
			{
				// Reset rules
				$this->form_validation->reset_rules();
				
				// Gan dieu kien cho cac bien
				$params = array('login');
				$this->form_validation->set_rules('login', 'login', 'callback__login_handle');
				
				// Xu ly du lieu
				if ($this->form_validation->run())
				{
					// Reset value
					$this->captcha_library->del('four');
					$this->matrix_library->position_del('admin');
					
					// Lay thong tin admin
					$admin = $this->data['admin'];
					
					// Ghi nho dang nhap
					if ($this->input->post('remember'))
					{
						admin_login_set_cookie($admin->username, $admin->password);
					}
					
					// Gan trang thai dang nhap
					admin_login_set($admin->id);
					
					// Tao url
					$url = $this->session->userdata('admin_url_return');
					if ($url)
					{
						$this->session->unset_userdata('admin_url_return');
					}
					else
					{
						$url = admin_url();
					}
					
					// Khai bao du lieu tra ve
					$result['complete'] = TRUE;
					$result['location'] = $url;
				}
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
		
		// Tao vi tri ngau nhien cua the xac thuc
		$matrix = FALSE;
		$config_admin_matrix = $this->setting_model->get('config-admin_matrix');
		if ($config_admin_matrix)
		{
			$matrix = $this->matrix_library->position_create('admin');
		}
		
		// Luu bien gui den view
		$this->data['action'] 	= current_url();
		$this->data['captcha'] 	= site_url('captcha/four');
		$this->data['matrix'] 	= $matrix;
		$this->data['matrix_length'] = 3;
		
		// Hien thi view
		$this->_display();
	}
	
	/**
	 * Kiem tra thong tin dang nhap
	 */
	function _login_handle()
	{
		// Lay du lieu dau vao
		$username 	= $this->input->post('username');
		$password 	= $this->input->post('password');
		$password 	= ($password) ? security_encode($password, strtolower($username)) : '';
		
		$matrix = array();
		$config_admin_matrix = $this->setting_model->get('config-admin_matrix');
		if ($config_admin_matrix)
		{
			$matrix_position = $this->matrix_library->position_get('admin', TRUE);
			$matrix_position[0] = implode('', $matrix_position[0]);
			$matrix_position[1] = implode('', $matrix_position[1]);
			
			$matrix[$matrix_position[0]] = $this->input->post('matrix_0');
			$matrix[$matrix_position[1]] = $this->input->post('matrix_1');
		}
		
		// Neu dang nhap khong thanh cong
		$login = admin_login($username, $password, $matrix);
		if ( ! $login['status'])
		{
			$error 	= $login['result']['error'];
			$ip 	= $this->input->ip_address();
			
			$lang = lang('notice_login_fail');
			if ($error == 'matrix')
			{
				$lang = lang('notice_matrix_fail');
			}
			elseif ($error == 'blocked')
			{
				$lang = lang('notice_account_blocked', $ip);
			}
			elseif ($error == 'ip_blocked')
			{
				$lang = lang('notice_ip_blocked', $ip);
			}
			elseif ($error == 'ip_blocked_login_fail')
			{
				$lang = lang('notice_ip_blocked_login_fail', $ip);
			}
			
			$this->form_validation->set_message(__FUNCTION__, $lang);
			
			return FALSE;
		}
		
		// Luu thong tin admin
		$this->data['admin'] = $login['result']['admin'];
		
		return TRUE;
	}
	
	/**
	 * Kiem tra ma bao mat
	 */
	function _check_security_code($value, $type)
	{
		$this->load->library('captcha_library');
		
		if ( ! $this->captcha_library->check($value, $type))
		{
			$this->form_validation->set_message(__FUNCTION__, lang('notice_value_incorrect'));
			return FALSE;
		}
		
		return TRUE;
	}
	
}