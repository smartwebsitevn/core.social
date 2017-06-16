<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Ket noi pay
 *
 * @author		hoangvantuyencnt@gmail.com
 * @version		05-12-2013
 */

class Ketnoipay_payment_card extends MY_Payment_card {
	
	var $setting 	= array(
							'TxtPartnerId' 			=> '',
							'TxtSignal' 			=> '',
						);
	
	public $code = 'ketnoipay';
	

	/**
	 * Test ket noi
	 */
	public function test()
	{
	    $type = 'viettel';
	    $code = now();
	    $serial = now();
	    $output = array();
	    $result = $this->check($type, $code, $serial, $output);
	
	    pr($output, 0);
	}
	
/*
 * ------------------------------------------------------
 *  Card handle
 * ------------------------------------------------------
 */
	/**
	 * Lay cac loai the ho tro
	 *
	 * @return array
	 */
	public function get_types()
	{
		$list = $this->_get_list_provider();

		return array_keys($list);
	}

	/**
	 * Thuc hien kiem tra the
	 * 
	 * @param string $type
	 * @param string $code
	 * @param string $serial
	 * @param array  $output
	 * @return boolean
	 */
	public function check($type, $code, $serial, &$output = array())
	{
	    $provider = $this->_get_provider($type);
	    
	    if ( ! $provider)
	    {
	        $output = 'Loại thẻ không hợp lệ';
	    
	        return false;
	    }
	    
	    $provider_url = $this->_get_url_provider($type); 
	    if ( ! $provider_url)
	    {
	        $output = 'Loại thẻ không hợp lệ'; 
	        return false;
	    }
	    
		// Lay card type
        $TxtType = $provider;
		$key  = $this->_knp_get_key($provider, $code);

        # Gửi thẻ lên máy chủ FPAY
        $TxtTransID = md5($this->setting_cur['TxtPartnerId'].rand().rand());

        $TxtKey   = md5(trim($this->setting_cur['TxtPartnerId'] . $TxtType . $TxtTransID . $code . $this->setting_cur['TxtSignal']));
        $key      = $TxtKey;

        // Khai bao cac bien
		$params = array(
					'TxtPartnerID'			=> $this->setting_cur['TxtPartnerId'],
					'TxtType'				=> $TxtType,
					'TxtMaThe'			    => $code,
					'TxtSeri'		        => $serial,
					'TxtTransId'		    => $TxtTransID,
					'TxtKey'				=> $key,
				);				
		
		// Thuc hien gui yeu cau
		$this->load->library('curl_library');
		$link  = $this->_knp_create_url($provider_url, $params);

		$param  = array();
		//$result = $this->curl_library->post($link, $param);	
		$result = file_get_contents($link);

		// Phan tich ket qua tra ve
		$api_data = array();
		$api_result = $this->_knp_check_result($result, $api_data);
		
		// Khai bao ket qua tra ve
		if ($api_result == true)
		{
		    $output['amount'] = $api_data['card_amount'];
		    $output['data'] = array(
		        'card_type'   	=> $type,
		        'card_provider' => $provider,
		        'card_code'   	=> $code,
		        'card_serial' 	=> $serial,
		        'card_amount' 	=> $api_data['card_amount'],
		        'request_id' 	=> $TxtTransID,
		    );
		    
		    return true;   
		}
		else 
		{
			$output = $api_data['error'];
			return false;
		}
		
	}
	

	/**
	 * Lay provider tuong ung voi type cua he thong
	 *
	 * @param string $type
	 * @return string
	 */
	protected function _get_provider($type)
	{
	    $data = $this->_get_list_provider();
	
	    return isset($data[$type]) ? $data[$type] : false;
	}
	
	
	/**
	 * Lay danh sach nha cung cap
	 *
	 * @return array
	 */
	protected function _get_list_provider()
	{
	    return array(
	        'viettel' 	=> 'VTT',
	        'mobi' 		=> 'VMS',
	        'vina' 		=> 'VNP',
	        'gate' 		=> 'GATE',
	    );
	}
	
	/**
	 * Lay danh sach nha cung cap
	 *
	 * @return array
	 */
	protected function _get_list_url_provider()
	{
	    return array(
	        'viettel' 	=> 'http://api.knp.vn/VIETTEL', //hoac http://api.knp.vn:64990
	        'mobi' 		=> 'http://api.knp.vn/VINAMOBI', //hoac http://api.knp.vn:64980
	        'vina' 		=> 'http://api.knp.vn/VINAMOBI', //hoac http://api.knp.vn:64980
	        'gate' 		=> 'http://api.knp.vn/GATE', //hoac http://api.knp.vn:64986
	    );
	}

	/**
	 * Lay provider tuong ung voi type cua he thong
	 *
	 * @param string $type
	 * @return string
	 */
	protected function _get_url_provider($type)
	{
	    $data = $this->_get_list_url_provider();
	
	    return isset($data[$type]) ? $data[$type] : false;
	}
	
	/**
	 * Tao key mã hóa
	 */
	private function _knp_get_key($provider,$code)
	{
		$key   = md5(trim($this->setting['TxtPartnerId'].$provider.$code.$this->setting['TxtSignal']));
	    return $key;
	} 
	
	/**
	 * Tao link gui toi ketnoipay
	 */
    private function _knp_create_url($url, $params)
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
	
	
	/**
	 * Kiem tra gia tri tra ve
	 */
	private function _knp_check_result($result, &$data = array())
	{
		//$result = 'RESULT:10@20000';
		$result = strval($result);
		if(strpos($result, 'RESULT:10') !== false)
		{
		    $data_result = str_replace('RESULT:10@','',$result);
		    $amount = intval($data_result);
		    if($amount > 0)
		    {
		        $data['card_amount'] = $amount;
		        return TRUE;
		    }
            // Neu that bai
            $data['error'] = $this->_knp_get_error($result);
            return FALSE;
		}
		
		// Neu that bai
		$data['error'] = $this->_knp_get_error($result);
		return FALSE;
	}
	
	/**
	 * Lay thong bao loi
	 */
	private function _knp_get_error($response)
	{
		
		$result = 'Lỗi không xác định';
		if(strpos($response,'RESULT:03') !== false || strpos($response,'RESULT:05') !== false || strpos($response,'RESULT:07') !== false || strpos($response,'RESULT:06') !== false) // thẻ sai
		{
			$result = 'Mã thẻ cào hoặc seri không chính xác.';
		}elseif(strpos($response,'RESULT:08') !== false)
		{
			$result = 'Thẻ đã gửi sang hệ thống rồi. Không gửi thẻ này nữa.';
		}elseif(strpos($response,'RESULT:12') !== false)
		{
			$result = 'Bạn phải nhập seri thẻ.';
		}elseif(strpos($response,'RESULT:11') !== false)
		{
			$result = 'Thẻ đã gửi sang hệ thống nhưng bị trễ.';
		}elseif(strpos($response,'RESULT:99') !== false || strpos($response,'RESULT:00') !== false || strpos($response,'RESULT:01') !== false || strpos($response,'RESULT:04') !== false || strpos($response,'RESULT:09') !== false)
		{
			$result = 'Hệ thống nạp thẻ đang bảo trì. Mã bảo trì là '.$response;
		}else{
			$result = 'Có lỗi xảy ra trong quá trình nạp thẻ. Vui lòng quay lại sau.';
		}
		return $result;
	}
}

?>