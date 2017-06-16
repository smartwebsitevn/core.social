<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Register_sms_gateway_handler
{
	/**
	 * Khoi tao doi tuong
	 */
	public function __construct()
	{
		t('lang')->load('sms_gateway_handler/register');
	}
	
	/**
	 * Xu ly sms
	 * 
	 * @param array $sms
	 * @return string
	 */
	public function handle(array $sms)
	{
		$username 	= array_get($sms, 'param');
		$phone 		= array_get($sms, 'phone');
		
		if ( ! mod('user')->valid_username($username, $error))
		{
			return $this->make_username_error($error, $username);
		}
		
		if (model('user')->has_user($phone))
		{
			return lang('phone_exists', compact('phone'));
		}
		
		$user = $this->create_user($username, $phone);
		
		return lang('register_success', $user);
	}
	
	/**
	 * Tao error username
	 * 
	 * @param string $error
	 * @param string $username
	 * @return string
	 */
	protected function make_username_error($error, $username)
	{
		if ($error == 'min_length')
		{
			return lang('username_min_length', array(
				'length' => model('user')->_password_lenght,
			));
		}
		
		return lang('username_'.$error, compact('username'));
	}
	
	/**
	 * Tao user email
	 * 
	 * @param string $username
	 * @return string
	 */
	protected function make_user_email($username)
	{
		$host = parse_url(site_url(), PHP_URL_HOST);
		return $username.'@'.$host;
	}
	
	/**
	 * Tao user
	 * 
	 * @param string $username
	 * @param string $phone
	 * @return array
	 */
	protected function create_user($username, $phone)
	{
		$data = array(
			'email' 	=> $this->make_user_email($username),
			'password' 	=> mod('user')->random_password(),
			'pin' 		=> mod('user')->random_password(),
			'name' 		=> $username,
			'username' 	=> $username,
			'phone' 	=> $phone,
		);
		
		mod('user')->create($data);
		
		return $data;
	}
	
}