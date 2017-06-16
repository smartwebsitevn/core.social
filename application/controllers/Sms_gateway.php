<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sms_gateway extends MY_Controller {
	
	/**
	 * Ham khoi dong 
	 */
	public function __construct()
	{
		parent::__construct();
		
		//debug_log_input();
	}
	
	/**
	 * Test
	 */
	public function t()
	{
		/* $v = $this->_handler('deposit', array(array(
			'syntax' 	=> 'localhost nt 123 abc',
			'param' 	=> '1',
			'port' 		=> '8765',
			'phone' 	=> '0123456789',
			'price' 	=> '15000',
		)));
		pr($v); */

		$sms = $this->_mod()->create('register', 'username4');
		//$sms = $this->_mod()->create('forgot');
		//$sms = $this->_mod()->create('forgot2');
		//$sms = $this->_mod()->create('deposit', '1');
		pr($sms, FALSE);
		
		//$v[0] = 'sMs rT zxcv ASDFG';
		$parse = $this->_mod()->parse($sms[0]);
		pr($parse, FALSE);
		
		//$sms[0] = 'VMG nap1 c24 sontung0';
		
		$data = array();
		$data['request_id']		= now();
		$data['mo_message'] 	= $sms[0];
		$data['msisdn'] 	    = '0169784584';
		$data['short_code'] 	= $sms[1];
		
		$url = site_url('sms_gateway').'?'.http_build_query($data);
		pr($url);
		
	}
	
	/**
	 * Home
	 */
	public function index()
	{
		// Kiem tra ip
		$service_ip = lib('sms_gateway')->get_service_ip();
		if ( ! is_null($service_ip))
		{
		    $service_ips = explode(',', $service_ip);
		    $service_ip  = array();
		    foreach ($service_ips as $ip)
		    {
		        $service_ip[] = trim($ip);
		    }
			//$service_ip = array('::1');
			$ip = $this->input->ip_address();
			if ( ! in_array($ip, (array) $service_ip))
			{
				$this->_result(lang('ip_invalid', $ip));
			}
		}
		
		// Kiem tra ket noi
		if ( ! lib('sms_gateway')->check_request())
		{
			$this->_result(lang('request_invalid'));
		}
		
		// Kiem tra cu phap
		$message = $this->_input('message');
		$sms = $this->_mod()->parse($message);
		if ( ! $sms)
		{
			$this->_result(lang('sms_invalid'));
		}
		
		// Kiem tra port
		$port = $this->_input('port');
		if ( ! in_array($port, (array) $sms['port']))
		{
			$this->_result(lang('sms_invalid_port', $port));
		}
		
		// Kiem tra phone
		$phone = $this->_input('phone');
		$phone = handle_phone($phone);
		if ( ! $phone)
		{
			$this->_result(lang('sms_invalid'));
		}
		
		// Kiem tra sms_id da ton tai hay chua
		$sms_id = $this->_input('sms_id');
		if (model('sms_receive')->has_sms($sms_id))
		{
			$this->_result(lang('sms_invalid_id'));
		}
	
		// Luu sms
		model('sms_gateway_log')->create(compact('sms_id', 'phone', 'message', 'port'));
		
		// Goi handler
		$result = $this->_handler($sms['mod'], array(array(
			'syntax' 	=> $message,
			'param' 	=> $sms['param'],
			'port' 		=> $port,
			'phone' 	=> $phone,
			'price' 	=> $this->_mod()->get_price($port),
		)));
		
		if ($result)
		{
			$this->_result($result);
		}
	}
	
	/**
	 * Lay sms input
	 * 
	 * @param string $param
	 * @return mixed
	 */
	protected function _input($param)
	{
		return lib('sms_gateway')->get_input_receive($param);
	}
	
	/**
	 * Gui ket qua tra ve
	 */
	protected function _result($content)
	{
		$this->_log($content);
		
		echo lib('sms_gateway')->make_feedback($content);
		exit();
	}
	
	/**
	 * Goi handler
	 * 
	 * @param string $handler
	 * @param array $args
	 * @return mixed
	 */
	protected function _handler($handler, array $args = array())
	{
		return call_user_func_array(array(
			t('lib')->driver('sms_gateway_handler', $handler),
			'handle'
		), $args);
	}
	
	/**
	 * Luu log
	 * 
	 * @param string $message
	 */
	protected function _log($message)
	{
		model('log_api')->log('sms_gateway_receive', array_filter(array(
			'get' 	=> t('input')->get(),
			'post' 	=> t('input')->post(),
		)), $message);
	}
	
}
