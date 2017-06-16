<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sms_otp extends MY_Controller {
	
	/**
	 * Ham khoi dong
	 */
	public function __construct()
	{
		parent::__construct();
		
		$this->lang->load('site/sms_otp');
	}
	
	function test()
	{
	    /*
	    //test gui otp theo loai
	    $result = mod('user_security')->send('withdraw');
	    var_dump($result);
	    */
	    //test hien thi form theo loai
	    echo mod('user_security')->form('withdraw');
	   
	    //test kiem tra gia tri theo loai
	    //$result = mod('user_security')->valid('withdraw', '781351');
	    //var_dump($result);
	}

	/**
	 * Gui lai tin nhan otp
	 *
	 * @return array
	 */
	function resend_otp()
	{
	    $mod = $this->uri->rsegment(3);
	    if(!in_array($mod, mod('user_security')->methods()) || !user_is_login())
	    {
			return $this->_resendResult(lang('notice_resend_otp_fail'));
	    }
	    //kiem tra loai xac thuc bao mat bao hanh dong
	    $user_security_type = setting_get('config-user_security_'.$mod);
	    if($user_security_type != 'sms_otp')
	    {
	        return $this->_resendResult(lang('notice_resend_otp_fail'));
	    }
	    
	    $user  = user_get_account_info();
	    $phone = $user->phone;
	    
	    //neu luot gui toi da tren ngay da het
	    $sms_odp_user = model('sms_otp_user')->get_info_rule(array('user_id' => $user->id));
	    $resend_sms = mod('sms_otp')->check_send_otp($sms_odp_user, 'resend_otp');
	    if(!$resend_sms)
	    {
	        return $this->_resendResult(lang('notice_resend_otp_fail_max_send'));
	    }
	    
	    mod('sms_otp')->send_otp($mod, $user, $phone, true);
	    
	    return $this->_resendResult(lang('notice_resend_otp_success'));
	   
	}
	
     /**
	 * Gui lai tin nhan odp
	 *
	 * @return array
	 */
	function resend_odp()
	{
	    $mod = $this->uri->rsegment(3);
	    if(!in_array($mod, mod('user_security')->methods()) || !user_is_login())
	    {
	        return $this->_resendResult(lang('notice_resend_otp_fail'));
	    }
	    
	    //kiem tra loai xac thuc bao mat bao hanh dong
	    $user_security_type = setting_get('config-user_security_'.$mod);
	    if($user_security_type != 'sms_odp')
	    {
	        return $this->_resendResult(lang('notice_resend_otp_fail'));
	    }
	    
	    $user  = user_get_account_info();
	    $phone = $user->phone;
	    
	    //neu luot gui toi da tren ngay da het
	    $sms_odp_user = model('sms_otp_user')->get_info_rule(array('user_id' => $user->id));
	    $resend_sms = mod('sms_otp')->check_send_otp($sms_odp_user, 'resend_odp');
	    if(!$resend_sms)
	    {
	        return $this->_resendResult(lang('notice_resend_odp_fail_max_send'));
	    }
	     
	    mod('sms_otp')->send_odp($mod, $user, $phone , true);

	    return $this->_resendResult(lang('notice_resend_odp_success'));
	}

	/**
	 * Tao ket qua tra ve
	 *
	 * @param string $message
	 */
	protected function _resendResult($message)
	{
		$this->data['message'] = $message;

		$this->_display('resend_result', null);
	}

}

