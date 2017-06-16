<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Fibo SMS
 *
 * @author		hoangvantuyencnt@gmail.com
 * @version		05-12-2013
 */
class Fibo_sms extends MY_Sms
{
	var $url_send_sms     = 'http://center.fibosms.com/Service.asmx/SendSMS';
	var $url_get_balance  = 'http://center.fibosms.com/Service.asmx/GetClientBalance';
	var $setting 	= array(
							'ClientNo' 			=> '',
							'ClientPass' 		=> '',
                            'serviceType'       => ''
						);
	
	
	/**
	 * Khoi tao doi tuong
	 */
	public function __construct()
	{
		$this->setting = array_merge($this->setting, config('setting_fibo', 'sms'));
	}
	
	/**
	 * Lay danh sach ip cua service
	 *
	 * @see MY_Sms::get_service_ip()
	 */
	public function get_service_ip()
	{
		return array('118.69.199.9', '112.78.7.18', '202.158.244.73', '203.171.30.222');
	}
	
	/**
	 * Lay input khi nhan thong tin tu service
	 *
	 * @see MY_Sms::get_input_receive()
	 */
	public function get_input_receive($param = NULL)
	{
		$data = array();
		$data['sms_id'] 	= $this->input->get('sms_id');
		$data['message'] 	= $this->input->get('message');
		$data['port'] 		= $this->input->get('port');
		$data['phone'] 		= $this->input->get('phone');
		
		return (is_null($param)) ? $data : $data[$param];
	}
	
	/**
	 * Gui phan hoi sau khi nhan yeu cau tu service
	 *
	 * @param string $content
	 */
	public function make_feedback($content)
	{
		return '
			<ClientResponse>
				<Message>
					<PhoneNumber>'.$this->input->get('phone').'</PhoneNumber>
					<Message>'.$content.'</Message>
					<SMSID>-1</SMSID>
					<ServiceNo>'.$this->input->get('service').'</ServiceNo>
				</Message>
			</ClientResponse>
		';
	}
	
	/**
	 * Thuc hien Gửi tin nhắn OTP
	 * @param string $phone		    Số điện thoại khách hàng
	 * @param string $smsMessage	Tin nhắn OTP gửi cho khách
	 * 
	 */				
	 public function send($phoneNumber, $smsMessage)
	 {
	 	$phoneNumber = $this->sms->make_phone($phoneNumber, 'short');
	 	
	 	  $smsGUID = $this->_fb_newguid();
	 	  $params = array(
	 	           'ClientNo' 		   => $this->setting['ClientNo'],
                   'ClientPass' 	   => $this->setting['ClientPass'],
                   'serviceType'       => $this->setting['serviceType'],
	 	           'phoneNumber'       => $phoneNumber,
	 	           'smsMessage'        => $smsMessage,
	 	           'smsGUID'           => $smsGUID,
	 	  );
	 	  $url  = $this->_fb_create_url($this->url_send_sms, $params);
	 	  
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
	public function get_balance()
	{
	 	  $params = array(
	 	           'ClientNo' 		   => $this->setting['ClientNo'],
				   'ClientPass' 	   => $this->setting['ClientPass'],
                   'serviceType'       => $this->setting['serviceType'],
	 	  );
	 	  $url  = $this->_fb_create_url($this->url_get_balance, $params);
	 	
	 	  $CI = & get_instance();
	 	  $CI->load->library('curl_library', NULL, 'curl'); 
		  $balance_sms   = $CI->curl->get($url); 
	
	 	  $balance_sms = simplexml_load_string($balance_sms);
	 	  $balance_sms = floatval($balance_sms);
	 	  return $balance_sms;
	}

	// --------------------------------------------------------------------
	 
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
	
	function _fb_writeFileLog($file_name, $data)
	{
		$fp = fopen($file_name,'a');
		if ($fp) {
			$line = date("H:i:s, d/m/Y:  ",time()).$data. " \n";
			fwrite($fp,$line);
			fclose($fp);
		}
	}
	
}