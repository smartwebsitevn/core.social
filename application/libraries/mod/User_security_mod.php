<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_security_mod extends MY_Mod
{
	/**
	 * Cac hanh dong can xac thuc
	 * 
	 * @return array
	 */
	public function methods()
	{
		return config('mods', 'mod/user_security');
	}

	/**
	 * Gui du lieu bao mat cho khach
	 *
	 * @param string $mod
	 * @param object $user
	 * @param string $phone
	 * @return bool
	 */
	public function send($mod, $user = null, $phone = null)
	{
	    if( ! in_array($mod, $this->methods()))
	    {
	        return false;
	    }
	    
	    //kiem tra loai xac thuc bao mat bao hanh dong
	    $user_security_type = setting_get('config-user_security_'.$mod);
	    switch ($user_security_type)
	    {
	        case 'password':
	            {
	                return true;
	            }
	        case 'pin':
	            {
	                return true;
	            }
	        case 'sms_otp':
	            {
	                return mod('sms_otp')->send_otp($mod, $user, $phone);
	            }
	        case 'sms_odp':
	            {
	                return mod('sms_otp')->send_odp($mod, $user, $phone);
	            }
	    }
	
	    return false;
	}

	/**
	 * Kiem tra gia tri bao mat
	 *
	 * @param string $mod
	 * @param string $input
	 * @param object $user
	 * @return bool
	 */
	public function valid($mod, $input = null, $user = null)
	{  
		if (is_null($input))
		{
			$input = t('input')->post($this->param());
		}
		
		if( ! in_array($mod, $this->methods()))
		{
		    return false;
		}

		//kiem tra loai xac thuc bao mat bao hanh dong
		$user_security_type = setting_get('config-user_security_'.$mod);
		switch ($user_security_type)
		{
			case 'password':
			{
				return mod('user')->is_password_current($input, $user);
			}
			case 'pin':
			{
				return mod('user')->is_pin_current($input, $user);
			}
			case 'sms_otp':
		    {
		        return mod('sms_otp')->is_otp_current($input, $user);
		    }
		    case 'sms_odp':
	        {
	            return mod('sms_otp')->is_odp_current($input, $user);
	        }
		}
		
		return false;
	}

	/**
	 * Hien thi form
	 *
	 * @param string $mod
	 * @param object $user
	 * @param string $param
	 * @return string|false
	 */
	public function form($mod, $user = null, $param = null)
	{
	    if(!in_array($mod, $this->methods()))
	    {
	        return false;
	    }
	    
	    if($user === null)
	    {
	        if ( ! user_is_login()) return false;
	        $user = user_get_account_info();
	    }
	    
	    //kiem tra loai xac thuc bao mat bao hanh dong
	    $method = setting_get('config-user_security_'.$mod);
	    
		t('lang')->load('site/user_security');
		
		$param = $param ?: $this->param();
		
		//lay thong tin sms odp cua thanh vien
		/*
		$resend_sms = false;
		$resend_sms_url = '';
		if(in_array($method, array('sms_otp', 'sms_odp')))
		{
		    $sms_odp_user = model('sms_otp_user')->get_info_rule(array('user_id' => $user->id));
		    $action = ($method == 'sms_otp') ? 'resend_otp' : 'resend_odp';
		    $resend_sms = mod('sms_otp')->check_send_otp($sms_odp_user, $action);
		    $resend_sms_url = site_url('sms_otp/'.$action.'/'.$mod);
		}
		*/

		$sms = [
			'forgot_otp' => mod('sms_gateway')->create('forgot_otp'),
			'forgot_odp' => mod('sms_gateway')->create('forgot_odp'),
		];
		
		return macro('tpl::user_security/macros')->form($method, compact('param', 'sms'/*, 'resend_sms', 'resend_sms_url'*/));
	}
	
	/**
	 * Ten bien trong form
	 * 
	 * @return string
	 */
	public function param()
	{
		return '_'.md5('user_security');
	}

	/**
	 * Lay rules
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'field' => $this->param(),
			'label' => lang('security_value'),
			'rules' => 'required',
		];
	}

	/**
	 * Lay thong bao error
	 *
	 * @return string
	 */
	public function getErrorMessage()
	{
		return lang('notice_value_incorrect', lang('security_value'));
	}

	/**
	 * Lay tên kiểu xác thưc
	 */
	function get_security_type($mod)
	{
		return setting_get('config-user_security_'.$mod);
	}
}