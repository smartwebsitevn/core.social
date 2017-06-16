<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pay365_payment_card extends MY_Payment_card
{
	public $code = 'pay365';
	
	public $setting = array(
		'PartnerID' 	=> '',
		'PartnerKey' 	=> '',
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
		$output['data']   = array(
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
			'viettel' 	=> 'viettel',
			'mobi' 		=> 'mobifone',
			'vina' 		=> 'vinaphone',
			'vnmobile' 	=> 'vietnammobile',
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
		
		return $this->_client()->check_card($provider, $code, $serial);
	}
	
	/**
	 * Tao request id
	 * 
	 * @return string
	 */
	protected function _request_id()
	{
		return date("YmdHms");
	}
	
	/**
	 * Lay maxpay client
	 * 
	 * @return Api_365pay
	 */
	protected function _client()
	{
		if (is_null($this->client))
		{
			require_once APPPATH.'libraries/365pay/api_365pay.php';
			$this->client = new Api_365pay($this->setting_cur);
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
		if ( ! empty($result->status) && ! empty($result->result))
		{
			$ouput['amount'] = $result->result->CardAmount;
			
			return true;
		}
		
		// That bai
		$ouput['error'] = isset($result->error) ? $result->error : 'Lỗi không xác định';
		
		return false;
	}

}