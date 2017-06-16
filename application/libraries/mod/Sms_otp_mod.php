<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sms_otp_mod extends MY_Mod
{
	/**
	 * Kiem tra ton tai
	 * 
	 * @param string $key
	 * @return boolean
	 */
	public function exists($key)
	{
		if ( ! $key) return false;
		
		$url = APPPATH.'libraries/sms_otp/'.$key.'_sms_otp'.EXT;
		
		return file_exists($url);
	}
	
	/**
	 * Kiem tra da duoc cai dat hay chua
	 * 
	 * @param string $key
	 * @return boolean
	 */
	public function installed($key)
	{
		if ( ! $key) return FALSE;
		
		$list_installed = model('sms_otp')->get_list_installed();
		
		return (in_array($key, $list_installed));
	}
	
	 /**
	 * Gui OTP bao mat cho khach
	 *
	 * @param string $mod
	 * @param object $user
	 * @param string $phone
	 * @return boolean
	 */
    function send_otp($mod, $user = null, $phone = null, $resend_otp = false)
    {
        if($user === null)
        {
            if ( ! user_is_login()) return false;
            $user = user_get_account_info();
        }
        if($phone === null)
        {
            $phone = $user->phone;
        }
        $user_id = $user->id;
        //lay thong tin sms otp cua thanh vien
        $sms_otp_user = model('sms_otp_user')->get_info_rule(array('user_id' => $user_id));
        
        //neu so lan gui otp cua thanh vien nay qua han toi da
        $type = ($resend_otp) ? 'resend_otp' : 'send_otp';
        if(!$this->check_send_otp($sms_otp_user, $type))
        {
            return false;
        }
        
        //tao ma otp va thuc hien gui
        $otp_code = $this->create_otp_code();
        $otp = security_encrypt($otp_code, 'decode');
        $result   = '';
        $message  = setting_get('config-user_security_sms_otp_message'); 
        $message  = str_replace('{code}', $otp, $message);
        
        $status  = lib('sms_otp')->send($phone, $message, $result);
        
        //cap nhat vao bang sms otp cua thanh vien
        $this->set_last_otp($otp_code, $sms_otp_user, $user_id, $type);
        
        //cap nhat vao bang log
        $data = array();
        $data['mod']     = $mod;
        $data['type']    = $type;
        $data['user_id'] = $user_id;
        $data['phone']   = $phone;
        $data['message'] = $message;
        $data['result']  = $result;
        if($status)
        {
            $data['status'] = config('sms_status_completed', 'main');
        }else{
            $data['status'] = config('sms_status_failed', 'main');
        }
        model('sms_otp_log')->create($data);
         
        return $status;
    }

    /**
     * Cap nhat lai OTP cuoi cua thanh vien
     * @param string $code
	 * @param int $user_id
	 * @param string $type
	 * 
     */
    function set_last_otp($code, $sms_otp_user, $user_id, $type)
    {
        $data = array();
        $data['last_otp'] = $code;
        $data['created_last_otp'] = now();
        //neu chua ton tai thi tao
        if(!$sms_otp_user)
        {
            $data['count_otp_send'] = 1;
            if($type == 'resend_otp')
            {
                $data['count_otp_resend'] = 1;
            }
            $data['user_id'] = $user_id;
            model('sms_otp_user')->create($data);
        }else{
            //kiem tra xem tin nhan gui lan cuoi co trong ngay khong,neu khong trong cung 1 ngay thi reset lan dem ve 1
            $created_last_otp = get_time_between(get_date($sms_otp_user->created_last_otp));
            //neu thoi gian gui hien tai ma khac voi ngay gui cuoi cung
            if(now() >= $created_last_otp[1])
            {
                $count_otp_send   = 1;
                $count_otp_resend = 1;
            }else{
                $count_otp_send   = $sms_otp_user->count_otp_send   + 1;
                $count_otp_resend = $sms_otp_user->count_otp_resend + 1;
            }
            //neu la resend otp thi cap nhat lai
            if($type == 'resend_otp')
            {
                $data['count_otp_resend'] = $count_otp_resend;
            }
            
            $data['count_otp_send'] = $count_otp_send;
            model('sms_otp_user')->update($sms_otp_user->id, $data);
        }
    }
    
    /**
     * Gui ODP bao mat cho khach
     *
     * @param string $mod
     * @param object $user
     * @param string $phone
     * @return boolean
     */
    function send_odp($mod, $user = null, $phone = null, $resend_odp = false)
    {
        if($user === null)
        {
            if ( ! user_is_login()) return false;
            $user = user_get_account_info();
        }
        if($phone === null)
        {
            $phone = $user->phone;
        }
        $user_id = $user->id;
        //lay thong tin sms odp cua thanh vien
        $sms_odp_user = model('sms_otp_user')->get_info_rule(array('user_id' => $user_id));
    
        //neu so lan gui odp cua thanh vien nay qua han toi da
        $type = ($resend_odp) ? 'resend_odp' : 'send_odp';
        if(!$this->check_send_otp($sms_odp_user, $type))
        {
            return false;
        }
    
        //tao ma odp va thuc hien gui
        $odp_code = $this->create_otp_code();
        $otp = security_encrypt($odp_code, 'decode');
        $result   = '';
        $message  = setting_get('config-user_security_sms_odp_message');
        $message  = str_replace('{code}', $otp, $message);
        
        $status  = lib('sms_otp')->send($phone, $message, $result);
        
        //cap nhat vao bang sms odp cua thanh vien
        $this->set_last_odp($odp_code, $sms_odp_user, $user_id, $type);
    
        //cap nhat vao bang log
        $data = array();
        $data['mod']     = $mod;
        $data['type']    = $type;
        $data['user_id'] = $user_id;
        $data['phone']   = $phone;
        $data['message'] = $message;
        $data['result']  = $result;
        if($status == true)
        {
            $data['status'] = config('sms_status_completed', 'main');
        }else{
            $data['status'] = config('sms_status_failed', 'main');
        }
        model('sms_otp_log')->create($data);
         
        return $status;
    }
    
    /**
     * Cap nhat lai ODP cuoi cua thanh vien
     * @param string $code
     * @param int $user_id
     * @param string $type
     */
    function set_last_odp($code, $sms_odp_user, $user_id, $type = '')
    {
        $data = array();
        $data['last_odp'] = $code;
        $data['created_last_odp'] = now();
        //neu chua ton tai thi tao
        if(!$sms_odp_user)
        {
            $data['count_odp_resend'] = 1;
            $data['user_id']        = $user_id;
            model('sms_otp_user')->create($data);
        }else{
            //kiem tra xem tin nhan gui lan cuoi co trong ngay khong,neu khong trong cung 1 ngay thi reset lan dem ve 1
            $created_last_odp = get_time_between(get_date($sms_odp_user->created_last_odp));
            //neu thoi gian gui hien tai ma khac voi ngay gui cuoi cung
            if(now() >= $created_last_odp[1])
            {
                $count_odp_resend = 1;
            }else{
                $count_odp_resend = $sms_odp_user->count_odp_resend + 1;
            }
            $data['count_odp_resend'] = $count_odp_resend;
            model('sms_otp_user')->update($sms_odp_user->id, $data);
        }
    }
    
    
    /**
     * Kiem tra xem co duoc phep gui ma OTP khong
     * @return boolean
     */
    function check_send_otp($sms_otp_user, $action = 'send_otp')
    { 
        if(!$sms_otp_user) return true;
        
        switch ($action)
        {
            case 'send_otp':
                {
                    $sms_otp_max_send = intval(setting_get('config-sms_otp_max_send'));
                    return ($sms_otp_user->count_otp_send < $sms_otp_max_send) ? true : false;
                }
            case 'send_odp':
                {
                    $created_last_odp = get_time_between(get_date($sms_otp_user->created_last_odp));
                    //neu thoi gian gui hien tai ma khac voi ngay gui cuoi cung
                    if(now() >= $created_last_odp[1])
                    {
                        return true;
                    }else{
                        return false;
                    }
                }   
            case 'resend_otp':
                {
                    $sms_otp_max_send = intval(setting_get('config-sms_otp_max_re_send'));
                    return ($sms_otp_user->count_otp_resend < $sms_otp_max_send) ? true : false;
                }
            case 'resend_odp':
                {
                    $sms_otp_max_send = intval(setting_get('config-sms_odp_max_re_send'));
                    return ($sms_otp_user->count_odp_resend < $sms_otp_max_send) ? true : false;
                }    
        }
        
        return false;
    }
    
	/**
	 * Kiem tra xem ma otp nhap vao co chinh xac
	 *
	 * @param string $otp
	 * @return boolean
	 */
	public function is_otp_current($otp,  $user = null)
	{
	    if($user === null)
	    {
	        if ( ! user_is_login()) return false;
	        $user = user_get_account_info();
	    }
	    
	    $sms_otp_user = model('sms_otp_user')->get_info_rule(array('user_id' => $user->id));
	    $last_otp = isset($sms_otp_user->last_otp) ? $sms_otp_user->last_otp : '';
	    //ma hoa va kiem tra
	    $last_otp = security_encrypt($last_otp, 'decode');
	    
	    return ($otp === $last_otp);
	}
	
	/**
	 * Kiem tra xem ma odp nhap vao co chinh xac
	 *
	 * @param string $odp
	 * @return boolean
	 */
	public function is_odp_current($odp, $user = null)
	{
	    if($user === null)
        {
            if ( ! user_is_login()) return false;
            $user = user_get_account_info();
        }
        
	    $sms_otp_user = model('sms_otp_user')->get_info_rule(array('user_id' => $user->id));
	    $last_odp = isset($sms_otp_user->last_otp) ? $sms_otp_user->last_odp : '';
	    //ma hoa va kiem tra
	    $last_odp = security_encrypt($last_odp, 'decode');
	    
	    return ($odp === $last_odp);
	}
	
	/**
	 * Tao ma OTP
	 *
	 * @return tring
	 */
	function create_otp_code()
	{
	    $code = strtolower(random_string('numeric', 6));
	    return security_encrypt($code, 'encode');
	}
}


