<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Baokim_payment_card extends MY_Payment_card
{
	public $code = 'baokim';
	
	public $setting = array(
		'merchant_id' 	=> '',
		'secure_pass' 	=> '',
		'api_username'	=> '',
		'api_password' 	=> '',
		'http_username' => '',
		'http_password' => '',
	);

	protected $_url = 'https://www.baokim.vn/the-cao/restFul/send';
	
	
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
			'viettel' 	=> 'VIETEL',
			'mobi' 		=> 'MOBI',
			'vina' 		=> 'VINA',
			'gate' 		=> 'GATE',
			'vcoin' 	=> 'VTC',
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
	 * cUrl
	 * 
	 * @param array $params
	 * @return array
	 */
	protected function _curl(array $params)
	{
		$curl = curl_init($this->_url);
		
		curl_setopt_array($curl, array(
			CURLOPT_POST => true,
			CURLOPT_HEADER => false,
			CURLINFO_HEADER_OUT => true,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_HTTPAUTH => CURLAUTH_DIGEST|CURLAUTH_BASIC,
			CURLOPT_USERPWD => $this->setting_cur['http_username'].':'.$this->setting_cur['http_password'],
			CURLOPT_POSTFIELDS => http_build_query($params),
		));
		
		$response = curl_exec($curl);
		$response = json_decode($response, true);
		
		$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		
		return compact('response', 'status');
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
		$request_id = date("YmdHms");
		
		$params = array(
			'merchant_id'   => $this->setting_cur['merchant_id'],
			'api_username'  => $this->setting_cur['api_username'],
			'api_password'  => $this->setting_cur['api_password'],
			'transaction_id'=> $request_id,
			'card_id'       => $provider,
			'pin_field'     => $code,
			'seri_field'    => $serial,
			'algo_mode'     => 'hmac',
		);
		
		ksort($params);
		
		$params['data_sign'] = hash_hmac('SHA1', implode('', $params), $this->setting_cur['secure_pass']);

		return $this->_curl($params);
	}

	/**
	 * Kiem tra ket qua tra ve tu api
	 * 
	 * @param array  $result
	 * @param array  $ouput
	 * @return boolean
	 */
	protected function _check_result($result, &$ouput)
	{
		$status 	= $result['status'];
		$response 	= $result['response'];
		
		// Thanh cong
		if ($status == 200)
		{
			$amount = $response['amount'];
			
			if (in_array($amount, array(
				10000, 20000, 30000, 50000, 
				100000, 200000, 300000, 500000, 1000000,
			)))
			{
				$ouput['amount'] = $amount;
				
				return true;
			}
		}
		
		// That bai
		$ouput['error'] = isset($response['errorMessage']) ? $response['errorMessage'] : 'Lỗi không xác định';
			
		return false;
	}

}