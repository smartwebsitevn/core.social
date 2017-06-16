<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Fibo SMS
 *
 * @author		hoangvantuyencnt@gmail.com
 * @version		18/3/2016
 */
class Fibo_sms_otp extends MY_Sms_otp
{
    
    public $code = 'fibo';
    
    public $setting = array(
        'client_no' 			=> '',
		'client_pass' 		    => '',
        'service_type'          => '',
        'sender_name'           => '', // neu khong dang ky thi bo trong
        'url'                   => ''
    );
   
	var $url_get_balance  = 'http://center.fibosms.com/Service.asmx/GetClientBalance';
    
   
    
/*
 * ------------------------------------------------------
 *  SMS handle
 * ------------------------------------------------------
 */
	/**
	 * Thuc hien Gửi tin nhắn OTP
	 * @param string $phone		    Số điện thoại khách hàng
	 * @param string $smsMessage	Tin nhắn OTP gửi cho khách
	 * 
	 */				
	 function send($phone, $smsMessage, &$result = '')
	 {
	 	  $smsGUID = $this->_fb_newguid();
	 	  $params = array(
	 	           'client_no' 		   => $this->setting['client_no'],
                   'client_pass' 	   => $this->setting['client_pass'],
                   'service_type'      => $this->setting['service_type'],
	 	  		   'sender_name'       => $this->setting['sender_name'],
	 	           'phoneNumber'       => $phone,
	 	           'smsMessage'        => $smsMessage,
	 	           'smsGUID'           => $smsGUID,
	 	  );
	 	  $url  = $this->_fb_create_url($this->setting['url'], $params);
	 	  
	 	  $CI = & get_instance();
	 	  $CI->load->library('curl_library', NULL, 'curl'); 
		  $data   = $CI->curl->get($url); 
		
	 	  $result = simplexml_load_string($data); 
		  $result = explode(' ', $result);
		 
		  $str = (isset($result['4'])) ? $result['4'] : '';
		  $str = (string)$str;
		  if(!strpos($str,'200'))
		  {
		  	  return FALSE;
		  }
		  return TRUE;  
	 }
	 
	
	 /**
	 * Thuc hien lay số dư hoặc số luợng tin nhắn còn lại
	 */
	 function get_balance()
	 {
	 	  $params = array(
	 	           'client_no' 		   => $this->setting['client_no'],
				   'client_pass' 	   => $this->setting['client_pass'],
                   'service_type'      => $this->setting['service_type'],
	 	  );
	 	  
	 	  $url  = $this->_fb_create_url($this->url_get_balance, $params);
	 	
	 	  $CI = & get_instance();
	 	  $CI->load->library('curl_library', NULL, 'curl'); 
		  $balance_sms   = $CI->curl->get($url); 
	
	 	  $balance_sms = simplexml_load_string($balance_sms);
	 	  $balance_sms = floatval($balance_sms);
	 	  return $balance_sms;
	 }
	
    /**
	 * Tao link gui toi FIBO
	 */
    private function _fb_create_url($url ,$params)
	{
		//Kiểm tra  biến $redirect_url xem có '?' không, nếu không có thì bổ sung vào
		$redirect_url = $url;
		if (strpos($redirect_url, '?') === FALSE)
		{
			$redirect_url .= '?';
		}
		else if (substr($redirect_url, strlen($redirect_url)-1, 1) != '?' && strpos($redirect_url, '&') === FALSE)
		{
			// Nếu biến $redirect_url có '?' nhưng không kết thúc bằng '?' và có chứa dấu '&' thì bổ sung vào cuối
			$redirect_url .= '&';			
		}
				
		// Tạo đoạn url chứa tham số
		$url_params = '';
		foreach ($params as $key => $value)
		{
			if ($url_params == '')
			{
				$url_params .= $key . '=' . urlencode($value);
			}
			else
			{
				$url_params .= '&' . $key . '=' . urlencode($value);
			}
		}
		
		return $redirect_url.$url_params;
	}
	
	private function _fb_newguid(){
	    mt_srand((double)microtime()*10000);
	    $charid = strtoupper(md5(uniqid(rand(), true)));
	    $hyphen = chr(45);// "-"
	    $uuid =  substr($charid, 0, 8).$hyphen
	            .substr($charid, 8, 4).$hyphen
	            .substr($charid,12, 4).$hyphen
	            .substr($charid,16, 4).$hyphen
	            .substr($charid,20,12);
	    return $uuid;
	}
	
	
}