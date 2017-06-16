<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Forgot2_sms_gateway_handler
{
	/**
	 * Khoi tao doi tuong
	 */
	public function __construct()
	{
		t('lang')->load('sms_gateway_handler/forgot');
	}
	
	/**
	 * Xu ly sms
	 * 
	 * @param array $sms
	 * @return string
	 */
	public function handle(array $sms)
	{
		$phone = array_get($sms, 'phone');
		
		$user = $this->get_user($phone);
		
		if ( ! $user)
		{
			return lang('user_not_exists', compact('phone'));
		}
		
		$pin = $this->reset_pin($user);
		
		return lang('forgot2_success', compact('pin'));
	}
	
	/**
	 * Lay user tuong ung voi phone
	 * 
	 * @param string $phone
	 * @return object|false
	 */
	protected function get_user($phone)
	{
		return model('user')->get_info_rule(compact('phone'));
	}
	
	/**
	 * Thuc hien reset pin
	 * 
	 * @param object $user
	 * @return string
	 */
	protected function reset_pin($user)
	{
		$pin = mod('user')->random_password();
		
		model('user')->update($user->id, array(
			'pin' => security_encode($pin),
		));
		
		return $pin;
	}
	
}