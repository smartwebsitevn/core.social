<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class User_security extends MY_Controller
{
	/**
	 * Ham khoi dong
	 */
	public function __construct()
	{
		parent::__construct();

		if (!user_is_login()) {
			redirect_login_return();
		}

		$this->lang->load('site/user');
	}

	/**
	 * Gan dieu kien cho cac bien
	 */
	protected function _set_rules($params)
	{
		$password_lenght = model('user')->_password_lenght;
		$rules = array();
		$rules['method'] = array('security_method', 'required|trim|callback__check_method');
		$rules['password_old'] = array('password_old', 'required|trim|callback__check_password_old');
		$rules['password'] = array('password', 'required|trim|xss_clean|min_length[' . $password_lenght . ']');
		$rules['password_confirm'] = array('password_confirm', 'required|trim|xss_clean|matches[password]');
		$rules['pin_old'] = array('pin_old', 'required|trim|callback__check_pin_old');
		$rules['pin'] = array('pin', 'required|trim|xss_clean|min_length[' . $password_lenght . ']');
		$rules['pin_confirm'] = array('pin_confirm', 'required|trim|xss_clean|matches[pin]');
		$rules[$this->_mod()->param()] = array('security_value', 'required|callback__check_user_security');
		$this->form_validation->set_rules_params($params, $rules);
	}

	/**
	 * Kiem tra method
	 */
	public function _check_method($value)
	{
		if (!in_array($value, $this->_mod()->methods(), true)) {
			$this->form_validation->set_message(__FUNCTION__, lang('notice_value_invalid'));
			return FALSE;
		}

		return TRUE;
	}

	/**
	 * Kiem tra user_security
	 */
	public function _check_user_security($value)
	{
		if (!$this->_mod()->valid( $this->data['key_confirm'])) {
			$this->form_validation->set_message(__FUNCTION__, lang('notice_value_incorrect'));
			return FALSE;
		}

		return TRUE;
	}

	/**
	 * Kiem tra mat khau cu
	 */
	public function _check_password_old($value)
	{
		if (!mod('user')->is_password_current($value)) {
			$this->form_validation->set_message(__FUNCTION__, lang('notice_value_incorrect'));
			return FALSE;
		}

		return TRUE;
	}

	/**
	 * Kiem tra pin cu
	 */
	public function _check_pin_old($value)
	{
		if (!mod('user')->is_pin_current($value)) {
			$this->form_validation->set_message(__FUNCTION__, lang('notice_value_incorrect'));
			return FALSE;
		}

		return TRUE;
	}

	// --------------------------------------------------------------------

	/**
	 * Home
	 */
	public function index()
	{
		$form = array();

		$form['validation']['params'] = array('method', $this->_mod()->param());

		$form['submit'] = function ($params) {
			$this->_index_submit();

			set_message(lang('notice_update_success'));

			return $this->_url();
		};

		$form['form'] = function () {
			$this->data['user'] = user_get_account_info();
			$this->data['methods'] = $this->_mod()->methods();

			page_info('title', lang('title_user_security'));

			$this->_display();
		};

		$this->_form($form);
	}

	/**
	 * Xu ly home submit
	 */
	protected function _index_submit()
	{
		$user = user_get_account_info();

		$security_method = $this->input->post('method');

		model('user')->update($user->id, compact('security_method'));
	}

	// --------------------------------------------------------------------

	/**
	 * Thay doi mat khau
	 */
	public function change_pass()
	{
		$form = array();

		$form['validation']['params'] = array('password_old', 'password', 'password_confirm');

		$form['submit'] = function ($params) {
			return $this->_change_pass_submit();


		};

		$form['form'] = function () {
			page_info('title', lang('title_change_pass'));

			$this->_display();
		};

		$this->_form($form);
	}

	/**
	 * Xu ly thay doi mat khau
	 */
	protected function _change_pass_submit()
	{
		$user = user_get_account_info();
		$password = $this->input->post('password');
		$password = mod('user')->encode_password($password, $user->email);


		$data['password'] = $password;
		t('session')->set_userdata('change_password', $data);
		$user_security_type = setting_get('config-user_security_change_password');
		if (in_array($user_security_type, config('types', 'mod/user_security'))) {
			mod('user_security')->send('change_password');
			$location = $this->_url('confirm/change_password');
		} else {

			model('user')->update($user->id, compact('password'));
			set_message(lang('notice_update_success'));
			$location = $this->_url('change_pass');

		}

		return $location;
	}

	// --------------------------------------------------------------------

	/**
	 * Tao pin
	 */
	public function make_pin()
	{
		if (!$this->_can_make_pin()) {
			$this->_redirect('change_pin');
		}

		$form = array();

		$form['validation']['params'] = array('password_old', 'pin', 'pin_confirm');

		$form['submit'] = function ($params) {
			$this->_change_pin_submit();

			set_message(lang('notice_update_success'));

			return $this->_url('change_pin');
		};

		$form['form'] = function () {
			page_info('title', lang('title_make_pin'));

			$this->_display();
		};

		$this->_form($form);
	}

	/**
	 * Kiem tra user hien tai co duoc tao pin hay khong
	 *
	 * @return boolean
	 */
	protected function _can_make_pin()
	{
		$user = user_get_account_info();

		$user = model('user')->get_info($user->id, 'pin');

		return ($user && !$user->pin);
	}

	// --------------------------------------------------------------------

	/**
	 * Thay doi pin
	 */
	public function change_pin()
	{
		if ($this->_can_make_pin()) {
			$this->_redirect('make_pin');
		}

		$form = array();
		/*$pin = $this->input->post('pin');
        if ($pin)
        {
            array_push($params, 'pin_old', 'pin', 'pin_repeat');
        }*/
		$form['validation']['params'] = array('pin_old', 'pin', 'pin_confirm');

		$form['submit'] = function ($params) {
			return $this->_change_pin_submit();
		};

		$form['form'] = function () {
			$pin_old = $this->input->get('pin_old');
			$this->data['pin_old'] = security_encrypt($pin_old, 'decode', '', true);

			page_info('title', lang('title_change_pin'));

			$this->_display();
		};

		$this->_form($form);
	}

	/**
	 * Xu ly thay doi mat khau
	 */
	protected function _change_pin_submit()
	{
		$user = user_get_account_info();
		$pin = $this->input->post('pin');
		$user_security_type = setting_get('config-user_security_change_pin');
		if (in_array($user_security_type, config('types', 'mod/user_security'))) {
			$data['pin'] = $pin;
			t('session')->set_userdata('change_pin', $data);
			mod('user_security')->send('change_pin');
			$location = $this->_url('confirm/change_pin');
		} else {

			$this->_set_pin($user->id, $pin);
			set_message(lang('notice_update_success'));
			$location = $this->_url('change_pin');
		}

		return $location;

	}
	/**
	 * Thuc hien gan pin cho user
	 *
	 * @param int $user_id
	 * @param string $pin
	 */
	protected function _set_pin($user_id, $pin)
	{
		$pin = security_encode($pin);

		model('user')->update($user_id, compact('pin'));
	}

	// --------------------------------------------------------------------

	/**
	 * Lay lai pin
	 */
	public function forgot_pin()
	{
		$form = array();

		$form['validation']['params'] = array('password_old');

		$form['submit'] = function ($params) {
			$this->_forgot_pin_submit();

			return $this->_url('forgot_pin');
		};

		$form['form'] = function () {
			page_info('title', lang('title_forgot_pin'));

			$this->_display();
		};

		$this->_form($form);
	}

	/**
	 * Xu ly quen pin
	 */
	protected function _forgot_pin_submit()
	{
		$user = user_get_account_info();

		$pin = random_string('numeric', 6);

		$this->_set_pin($user->id, $pin);

		mod('email')->send('forgot_pin', $user->email, array(
			'user_email' => $user->email,
			'user_name' => $user->name,
			'pin' => $pin,
			'url_change' => $this->_url('change_pin') . '?' . http_build_query(array(
					'pin_old' => security_encrypt($pin, 'encode', '', true)
				)),
		));

		set_message(lang('notice_send_pin_success', $user->email));
	}


	// xac thuc hanh dong

	function confirm($type)
	{
		$data = t('session')->userdata($type);
		if (!$data) {
			redirect();
		}

		$this->data['key_confirm'] = $type;

		// Tai cac file thanh phan
		$this->load->library('form_validation');
		$this->load->helper('form');

		// Xu ly form
		if ($this->input->post('_submit')) {
			$user = user_get_account_info();
			// Gan dieu kien cho cac bien
			$params = array($this->_mod()->param());
			$this->_set_rules($params);

			// Xu ly du lieu
			$result = array();
			if ($this->form_validation->run()) {

				// Khai bao du lieu tra ve
				$result['complete'] = TRUE;
				if($type == "change_password"){
					model('user')->update($user->id, $data);
					$result['location'] = $this->_url('change_pass');
				}
				elseif($type == "change_pin"){
					$this->_set_pin($user->id, $data["pin"]);
					$result['location'] = $this->_url('change_pin');

				}
				else
					$result['location'] = site_url('user');

				set_message(lang('confirmComplate'));
			} else {
				foreach ($params as $param) {
					$result[$param] = form_error($param);
				}
			}

			// Form output
			$this->_form_submit_output($result);
		}

		// Hien thi view
		$this->_display();
	}
}
