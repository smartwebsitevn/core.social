<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Forgot_odp_sms_gateway_handler
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
		
		$odp = $this->reset_odp($user);
		
		return lang('forgot_odp_success', compact('odp'));
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
	 * Thuc hien reset odp
	 * 
	 * @param object $user
	 * @return string
	 */
	protected function reset_odp($user)
	{
	    //tao ma odp
		$odp = mod('sms_otp')->create_otp_code();
		$last_odp = security_encrypt($odp, 'decode');
		 
		 //lay thong tin sms odp cua thanh vien
        $sms_otp_user = model('sms_otp_user')->get_info_rule(array('user_id' => $user->id));
        //cap nhat lai ma odp
		mod('sms_otp')->set_last_odp($odp, $sms_otp_user, $user->id);
		return $last_odp;
	}
}
