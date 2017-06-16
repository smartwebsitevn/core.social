<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_verify extends MY_Controller
{
	/**
	 * Ham khoi dong
	 */
	public function __construct()
	{
		parent::__construct();

		if ( ! user_is_login())
		{
			redirect_login_return();
		}

		$this->lang->load('site/user');
	}
	
	/**
	 * Gan dieu kien cho cac bien
	 */
	protected function _set_rules($params)
	{
		$rules = array();
		
		foreach ($this->_get_params() as $p)
		{
			$rules[$p] = array($p, 'required|trim|xss_clean');
		}
		
		$rules['paypal_emails'] = array('paypal_emails', 'trim|xss_clean');
		
		$this->form_validation->set_rules_params($params, $rules);
	}
	
	/**
	 * Lay danh sach bien
	 * 
	 * @return array
	 */
	protected function _get_params()
	{
		return array('name', 'phone', 'address', 'card_no', 'card_place', 'card_date', 'paypal_emails');
	}
	
	/**
	 * Lay input
	 */
	protected function _get_input($key = null, $default = null)
	{
		$input = array_only(t('input')->post(), $this->_get_params());
		
		$input['paypal_emails'] = $this->_get_paypal_emails();
		
	    return array_get($input, $key, $default);
	}
	
	/**
	 * Lay paypal_emails
	 * 
	 * @return array
	 */
	protected function _get_paypal_emails()
	{
		$this->load->helper('email');
		
		$list = $this->input->post('paypal_emails');

		$list = explode("\n", $list);
		$list = array_map('trim', $list);
		
		$list = array_filter($list, function($val)
		{
			return valid_email($val);
		});
		
		return array_unique($list);
	}
	
	/**
	 * Kiem tra user co duoc phep xac thuc hay khong
	 * 
	 * @return boolean
	 */
	protected function _can_verify()
	{
		$user = user_get_account_info();
		
		return (
			user_can_do($user, 'verify')
			|| user_can_do($user, 'verify_edit')
		);
	}
	
	/**
	 * Lay thong tin xac thuc
	 * 
	 * @param int $user_id
	 * @return object|false
	 */
	protected function _get_verify($user)
	{
		$data = $this->_model()->get($user->id);
		
		if ($data)
		{
			$data->paypal_emails = @unserialize($data->paypal_emails);
		}
		else 
		{
			$data = (object) array_only((array) $user, $this->_get_params());
		}
		
		return $data;
	}

	// --------------------------------------------------------------------
	
	/**
	 * Verify
	 */
	public function verify()
	{
		if ( ! $this->_can_verify())
		{
			$this->_redirect();
		}
		
		$form = array();
		
		$form['validation']['params'] = $this->_get_params();
		
		$form['submit'] = function()
		{
			return $this->_verify_submit();
		};
		
		$form['form'] = function()
		{
			$this->_verify_view();
		};

		$this->_form($form);
	}
	
	/**
	 * Verify submit
	 * 
	 * @return string
	 */
	protected function _verify_submit()
	{
		$user = user_get_account_info();
		
		$data = $this->_get_input();
		$data['paypal_emails'] 	= serialize($data['paypal_emails']);
		$data['created'] 		= now();
		
		$this->_model()->set($user->id, $data);
		
		model('user')->update($user->id, array(
			'verify' => config('user_verify_wait', 'main'),
		));
		
		set_message(lang('notice_verify_success'));
		
		return $this->_url('verify');
	}
	
	/**
	 * Verify view
	 */
	protected function _verify_view()
	{
		$user = user_get_account_info();
		
		$this->data['user'] = $user;
		$this->data['user_verify'] = $this->_get_verify($user);
		
		page_info('title', lang('title_verify_verify'));
		
		$this->_display();
	}

	// --------------------------------------------------------------------
	
	/**
	 * Home
	 */
	public function index()
	{
		if ($this->_can_verify())
		{
			$this->_redirect('verify');
		}
		
		$user = user_get_account_info();
		
		$this->data['user'] = $user;
		$this->data['user_verify'] = $this->_get_verify($user);
		
		page_info('title', lang('title_verify'));
		
		$this->_display();
	}
	
}
