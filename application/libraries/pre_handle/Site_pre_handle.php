<?php use App\User\UserFactory;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Site_pre_handle extends MY_Pre_handle
{
	/**
	 * Goi cac ham xu ly
	 */
	public function boot()
	{
		// Load cac file thanh phan
		$this->load->helper('site');
		$this->lang->load('site/common');
		
		// Bao tri he thong
		$this->offline();

		$this->_log_access();

		// Kiem tra user hien tai
		if ( ! $this->user() && user_is_login())
		{
			user_logout();
			
			redirect();
		}
		
		// thong ke truy cap
       	//$this->counter();
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Chuyen den trang offline neu he thong dang bao tri
	 */
	public function offline()
	{
		if ($this->is_admin()) return;
		
		// Lay setting
		$maintenance = $this->setting_model->get('config-maintenance');
		
		// Chuyen den trang offline
		$controller = $this->uri->rsegment(1);
		$controller = strtolower($controller);
		if ($maintenance == config('status_on', 'main') && $controller != 'offline')
		{
			redirect('offline');
		}
		
		// Neu he thong dang hoat dong thi khong cho phep truy cap trang offline
		elseif ($maintenance == config('status_off', 'main') && $controller == 'offline')
		{
			redirect();
		}
	}
	
	// --------------------------------------------------------------------

	/**
	 * Kiem tra user hien tai
	 */
	public function user()
	{
		// Tai file thanh phan
		$this->load->helper('user');
	
		// Kiem tra session
		$id = t('session')->userdata('__user_id');
		if ( ! $id)
		{
			// Neu khong ton tai session thi kiem tra cookie
			if ( ! user_login_check_cookie())
			{
				return FALSE;
			}
				
			// Lay user_id trong session
			$id = t('session')->userdata('__user_id');
		}
	
		// Lay thong tin cua thanh vien hien tai
		$info = model('user')->get_info($id);
		if ( ! $info)
		{
			return FALSE;
		}

		// Tai khoan bi khoa
		if ($info->blocked == config('verify_yes', 'main'))
		{
			$this->lang->load('site/user');
			set_message(lang('notice_account_blocked'));
			return FALSE;
		}
	
		// Tai khoan chua duoc kich hoat
		if (mod('user')->setting('register_require_activation') && ! $info->activation)
		{
			$this->lang->load('site/user');
			set_message(lang('notice_account_not_activation'));
			return FALSE;
		}
		
		// Neu khong phai la admin thi kiem tra ip hien tai
		if (
			mod('user')->setting('login_check_ip')
			&& ! $this->is_admin()
			&& t('input')->ip_address() != $info->ip
		)
		{
			$this->lang->load('site/user');
			set_message(lang('notice_account_logout_ip'));
			return FALSE;
		}

//		$url = current_url();
//		if ( ! user_permission_url($url))
//		{
//			set_message(lang('notice_do_not_have_permission'));
//
//			if ($this->input->is_ajax_request())
//			{
//				$output = json_encode(array());
//				set_output('json', $output);
//			}
//			redirect();
//
//		}

		// Kiem tra tran thai module
//		$this->check_module();


		return TRUE;
	}

	/**
	 * Kiem tra acc hien tai co phai la admin hay khong
	 * 
	 * @return boolean
	 */
	protected function is_admin()
	{

		//return admin_is_login();
		return t('session')->userdata('__admin_id') ? true : false;
	}

	protected function check_module()
	{

		// ko check voi admin
		if($this->is_admin())
			return true;

		$module=t('uri')->rsegment(1);
		if(module_can_access($module))
			return true;
		redirect();
	}

	function counter()
	{

       $this->load->library('statistic_library', NULL, 'Lstatistic');
       $this->Lstatistic->counter();
    }

	/**
	 * Luu log truy cap
	 */
	protected function _log_access()
	{
		$user = UserFactory::auth()->user();

		if ( ! $user->id) return;

		// Them vao log
		model('log_access')->add('user', (int) $user->id);

		// Xoa log cach day 1 thang
		model('log_access')->cleanup('user', '', 1*30*24*60*60);
	}
}