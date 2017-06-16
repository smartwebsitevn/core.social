<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Forgot_otp_sms_gateway_handler
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
		
		$otp = $this->reset_otp($user);
		
		return lang('forgot_otp_success', compact('otp'));
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
	 * Thuc hien reset otp
	 * 
	 * @param object $user
	 * @return string
	 */
	protected function reset_otp($user)
	{
	    //tao ma otp
		$otp = mod('sms_otp')->create_otp_code();
		$last_odp = security_encrypt($otp, 'decode');
			
		 //lay thong tin sms otp cua thanh vien
        $sms_otp_user = model('sms_otp_user')->get_info_rule(array('user_id' => $user->id));
        //cap nhat lai ma otp
		mod('sms_otp')->set_last_otp($otp, $sms_otp_user, $user->id, 'resend_otp');
		return $last_odp;
	}
	
}