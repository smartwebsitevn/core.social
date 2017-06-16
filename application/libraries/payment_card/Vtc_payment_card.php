<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Vtc_payment_card extends MY_Payment_card
{
	public $code = 'vtc';
	
	public $setting = array(
		'PartnerID' 	=> '',
		'PartnerKey' 	=> '',
	);
	
	protected $_url = 'http://api.vtcebank.vn:8888/VMSCardAPI/card.asmx?wsdl';
	//protected $_url = 'http://sandbox2.vtcebank.vn/WSCardTelco/card.asmx'; // Test
	
	
	// --------------------------------------------------------------------
	
	/**
	 * Test ket noi
	 */
	public function test()
	{
		$provider = $this->_get_provider('viettel');
		$code = now();
		$serial = now();
		
		$result = $this->_request($provider, $code, $serial);
		
		pr($result, 0);
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
		
		$result = $this->_request($provider, $code, $serial, $request_id);
		
		$result = $this->_parse_result($result);
		
		if ( ! $this->_check_result($result, $response))
		{
			$output = $response['error'];
			
			return false;
		}
		
		$output['amount'] = $response['amount'];
		$output['data'] = array(
			'card_type'   	=> $type,
			'card_provider' => $provider,
			'card_code'   	=> $code,
			'card_serial' 	=> $serial,
			'card_amount' 	=> $response['amount'],
			'request_id' 	=> $request_id,
		);
		
		return true;
	}

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
	
	// --------------------------------------------------------------------
	
	/**
	 * Lay danh sach nha cung cap
	 * 
	 * @return array
	 */
	protected function _get_list_provider()
	{
		return array(
			'viettel' 	=> 'VTEL',
			'mobi' 		=> 'VMS',
			'vina' 		=> 'GPC',
		);
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
	 * Tao doi tuong soap client
	 * 
	 * @return nusoap_client
	 */
	protected function _soap()
	{
		require_once APPPATH.'libraries/nusoap/nusoap.php';
		
		$soap = new nusoap_client($this->_url, true);
		
		return $soap;
	}
	
	/**
	 * Request data
	 * 
	 * @param string $provider
	 * @param string $code
	 * @param string $serial
	 * @param string $request_id
	 * @return mixed
	 */
	protected function _request($provider, $code, $serial, &$request_id = null)
	{
		$request_id = $provider.'|'.now().'|'.$code;
		
		$request_data = $this->_make_data_request($serial, $code, $request_id);
		
		return $this->_soap()->call('Request', array(
			'PartnerID' 	=> $this->setting_cur['PartnerID'],
			'RequestData' 	=> $request_data,
		));
	}
	
	/**
	 * Phan tich ket qua tra ve tu api
	 * 
	 * @param array $result
	 * @return SimpleXMLElement
	 */
	protected function _parse_result($result)
	{
		$result = $this->_decrypt($result['RequestResult']);
		
		$result = preg_replace('/(<\?xml[^?]+?)utf-16/i', '$1utf-8', $result);
		
		$result = simplexml_load_string($result);
		
		return $result;
	}
	
	/**
	 * Tao data request
	 * 
	 * @param unknown $CardID
	 * @param unknown $CardCode
	 * @param unknown $Description
	 */
	protected function _make_data_request($CardID, $CardCode, $Description)
	{
		$data = '<?xml version="1.0" encoding="utf-16"?>
			<CardRequest>
				<Function>UseCard</Function>
				<CardID>'.$CardID.'</CardID>
				<CardCode>' .$CardCode. '</CardCode>
				<Description>'.$Description.'</Description>
			</CardRequest>';
		
		return $this->_encrypt($data);
	}

	/**
	 * Kiem tra ket qua tra ve tu api
	 * 
	 * @param object $result
	 * @param array  $ouput
	 * @return boolean
	 */
	protected function _check_result($result, &$ouput)
	{
		// Khong xac dinh
		if ( ! isset($result->responsestatus))
		{
			$ouput['error'] = 'Lỗi không xác định';
			
			return false;
		}
		
		$status = (int) $result->responsestatus;
		
		// Thanh cong
		if ($status > 0)
		{
			$ouput['amount'] = $status;
			
			return true;
		}
		
		// That bai
	    switch($status)
		{
			case -1:
				$ouput['error'] = 'Thẻ đã sử dụng';
				return false;
				break;
			break;
			case -2:
				$ouput['error'] = 'Thẻ đã bị khóa';
				return false;
				break;
			break;
			case -3:
				$ouput['error'] = 'Thẻ hết hạn sử dụng';
				return false;
				break;
			break;
			case -4:
				$ouput['error'] = 'Thẻ chưa kích hoạt';
				return false;
				break;
			break;
			case -5:
				$ouput['error'] = 'TransID không hợp lệ';
				return false;
				break;
			break;	
			case -6:
				$ouput['error'] = 'Mã thẻ và số Serial không khớp';
				return false;
				break;	
			break;
			case -8:
				$ouput['error'] = 'Cảnh báo số lần giao dịch lỗi của một tài khoản';
				return false;
				break;	
			break;
			case -9:
				$ouput['error'] = 'Thẻ thử quá số lần cho phép';
				return false;
				break;
			break;
			case -10:
				$ouput['error'] = 'Số serial không hợp lệ';
				return false;
				break;
			break;
			case -11:
				$ouput['error'] = 'Mã thẻ không hợp lệ';
				return false;
				break;
			break;
			case -12:
				$ouput['error'] = 'Thẻ không tồn tại';
				return false;
				break;
			break;
			case -13:
				$ouput['error'] = 'Invalid Descriptions Format';
				return false;
				break;
			break;
			case -14:
				$ouput['error'] = 'Telco Code not exist';
				return false;
				break;
			break;
			case -15:
				$ouput['error'] = 'Missing customer information';
				return false;
				break;
			break;
			case -16:
				$ouput['error'] = 'Invalid TransactionID';
				return false;
				break;
			break;
			case -90:
				$ouput['error'] = 'Sai tên hàm';
				return false;
				break;
			break;
			case -98:
			case -99:
			case -100:
				$ouput['error'] = 'Lỗi hệ thống';
				return false;
				break;
			break;
			case -999:
				$ouput['error'] = 'Telco System pause';
				return false;
				break;
			break;
		}
		
		return false;
	}
	
	/**
	 * Ham mã hóa
	 */
	protected function _encrypt($input)
 	{
	    $input = trim($input);
	    $block = mcrypt_get_block_size('tripledes', 'ecb');
	    $len = strlen($input);
	    $padding = $block - ($len % $block);
	    $input .= str_repeat(chr($padding),$padding);  
	    // generate a 24 byte key from the md5 of the seed
	    $key = substr(md5($this->setting_cur['PartnerKey']),0,24);
	    $iv_size = mcrypt_get_iv_size(MCRYPT_TRIPLEDES, MCRYPT_MODE_ECB);
	    $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
	    // encrypt
	    $encrypted_data = mcrypt_encrypt(MCRYPT_TRIPLEDES, $key, $input, MCRYPT_MODE_ECB, $iv);
	    // clean up output and return base64 encoded
	    
	    return base64_encode($encrypted_data);
	} 
	
	/**
	 * Ham giải mã
	 */
	protected function _decrypt($input)
	{
		$input = base64_decode($input);
		
		$key = substr(md5($this->setting_cur['PartnerKey']), 0, 24);
		$text = mcrypt_decrypt(MCRYPT_TRIPLEDES, $key, $input, MCRYPT_MODE_ECB, '12345678');
		$block = mcrypt_get_block_size('tripledes', 'ecb');
		$packing = ord($text{strlen($text) - 1});
		
		if ($packing and ($packing < $block))
		{
			for ($P = strlen($text) - 1; $P >= strlen($text) - $packing; $P --)
			{
				if (ord($text{$P}) != $packing)
				{
					$packing = 0;
				}
			}
		}
		
		$my_string = strtolower(substr($text, 0, strlen($text) - $packing));
		
		return $my_string;
	}

}