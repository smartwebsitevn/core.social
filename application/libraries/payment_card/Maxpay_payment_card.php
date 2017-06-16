<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Maxpay_payment_card extends MY_Payment_card
{
	public $code = 'maxpay';
	
	public $setting = array(
		'merchant_id' 	=> '',
		'secret_key' 	=> '',
	);
	
	protected $client;
	
	
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
			'tran_id' 		=> $response['tran_id'],
			'tran_amount' 	=> $response['tran_amount'],
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
			'viettel' 	=> 'VTE',
			'mobi' 		=> 'VMS',
			'vina' 		=> 'VNP',
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
		$request_id = $this->_request_id();
		
		$log_id = model('log_api')->log_input(
					'maxpay_payment_card',
					compact('provider', 'code', 'serial', 'request_id')
				);
		
		$result = $this->_client()->charge($request_id, $provider, $code, $serial);
		
		model('log_api')->log_output($log_id, $result);
		
		return $result;
	}
	
	/**
	 * Tao request id
	 * 
	 * @return string
	 */
	protected function _request_id()
	{
		return random_string('unique');
	}
	
	/**
	 * Lay maxpay client
	 * 
	 * @return MaxpayClient
	 */
	protected function _client()
	{
		if (is_null($this->client))
		{
			require_once APPPATH.'libraries/maxpay/MaxpayClient.php';
			
			$this->client = new MaxpayClient($this->setting_cur);
		}
		
		return $this->client;
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
		// Thanh cong
		if (isset($result['code']) && $result['code'] == 1)
		{
			$ouput['amount'] 		= $result['card_amount'];
			$ouput['tran_id'] 		= $result['txn_id'];
			$ouput['tran_amount'] 	= $result['net_amount'];
			
			return true;
		}
		
		// That bai
		$ouput['error'] = isset($result['message']) ? $result['message'] : 'Lỗi không xác định';
		
		return false;
	}

}