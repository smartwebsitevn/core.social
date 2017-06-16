<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_pre_handle extends MY_Pre_handle {
	static $admin =null;
	/**
	 * Admin pre handle
	 */
	function boot()
	{
		// Load cac file thanh phan
		$this->load->helper('admin');
		$this->lang->load('admin/common');
		
		// Kiem tra admin hien tai
		$this->admin();
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Kiem tra admin hien tai
	 */
	function admin()
	{
		// Kiem tra admin da login hay chua
		$is_login = $this->_admin_login();
		
		// Lay controller hien tai
		$controller = $this->uri->rsegment(1);
		$controller = strtolower($controller);
		
		// Neu controller hien tai khong phai la login ma client chua login thi chuyen den trang login
		if ($controller != 'login' && ! $is_login)
		{
			// Luu current url vao session
			$url_cur = current_url(TRUE);
			$this->session->set_userdata('admin_url_return', $url_cur);
			
			redirect_admin('login');
		}
		// Neu controller hien tai la login ma client da dang nhap thi chuyen den trang home
		elseif ($controller == 'login' && $is_login)
		{
			redirect_admin();
		}
		
		// Neu da login
		if ($is_login)
		{
			// Tai khoan bi khoa
			if (static::$admin->blocked == config('verify_yes', 'main'))
			{
				$this->session->unset_userdata('admin_id');
				//set_message(lang('notice_account_blocked'));
				redirect_admin('login');
			}

			// Kiem tra tran thai module
			//$this->_admin_check_module();

			// Kiem tra quyen truy cap
			$this->_admin_permissions();
			
			// Luu log truy cap (su dung log bang file, khong dung hinh thuc luu vao db tranh nang he thong)
			$this->_admin_log_access();
		}
	}
	
	/**
	 * Kiem tra thong tin login cua admin
	 */
	protected function _admin_login()
	{
		// Neu chua login va cookie khong phu hop
		if (
		 	! admin_is_login() &&
			! admin_login_check_cookie()
		)
		{
			return FALSE;
		}
		static::$admin= admin_get_account_info();
		// Neu khong get duoc thong tin admin hien tai
		if ( ! static::$admin)
		{
			return FALSE;
		}

		return TRUE;
	}
	
	/**
	 * Kiem tra quyen truy cap
	 */
	protected function _admin_permissions()
	{
		$url = current_url();
		if ( ! admin_permission_url($url))
		{
			$tmp= admin_parse_url($url);

			// neu la dang xuat khoi tai khoan
			if($tmp[0] == 'home' && $tmp[1] == 'logout'){
				return true;

			}

			if($tmp[0] == 'home' && $tmp[1] == 'index')
				redirect_admin('home/blank');


			$meg=lang('notice_do_not_have_permission');
			set_message($meg);

			if ($this->input->is_ajax_request())
			{
				$output = json_encode(array('error'=>$meg));
				set_output('json', $output);
			}


			redirect_admin();
		}
	}

	/**
	 * Luu log truy cap
	 */
	protected function _admin_log_access()
	{
		// Tai file thanh phan
		$this->load->model('log_access_model');
		
		// Them vao log
		$admin = admin_get_account_info();
		$this->log_access_model->add('admin', $admin->id);
		
		// Xoa log cua admin cach day 1 thang
		$this->log_access_model->cleanup('admin', '', 1*30*24*60*60);
	}

	protected function _admin_check_module()
	{
		// ko check voi user root
		//if(static::$admin->is_root)			return true;

		$module=t('uri')->rsegment(1);
		if(module_can_access($module))
			return true;
		redirect_admin();
	}

}