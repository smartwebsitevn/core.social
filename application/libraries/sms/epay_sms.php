<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Epay_sms extends MY_sms
{
	public $setting	= array(
		'username'		=> '',
		'password' 		=> '',
	);
	
	protected $_url = 'http://brandsms.vn:8329/SentSMS.asmx?wsdl';
	protected $_client;
	
	
	/**
	 * Khoi tao doi tuong
	 */
	public function __construct()
	{
		$this->setting = array_merge($this->setting, config('setting_epay', 'sms'));
	}
	
	/**
	 * Gui sms
	 *
	 * @param string $phone		So dien thoai nhan sms
	 * @param string $msg		Noi dung sms
	 * @param string $sender	Ten nguoi gui
	 * @return bool
	 */
	public function send($phone, $msg, $sender = '')
	{
		// Tao nusoap_client
		$this->_make_client();
		
		// Goi api
		$result = $this->_client->call('SendSmsChamSocKhachHang', array(
			'msisdn'  => $phone,
			'alias'   => $sender,
			'message' => $msg,
			'contentType'=> '0',
			'authenticateUser' => $this->setting['username'],
			'authenticatePass' => $this->setting['password'],
		), '', '', '');
		
		// Neu that bai
		if ($this->_client->fault || $this->_client->getError())
		{
			return FALSE;
		}
		
		return TRUE;
	}
	
	/**
	 * Tao doi tuong nusoap_client
	 * 
	 * @return nusoap_client
	 */
	protected function _make_client()
	{
		if (is_null($this->_client))
		{
			require_once APPPATH.'libraries/nusoap/nusoap.php';
				
			$this->_client = new nusoap_client($this->_url, 'wsdl', '', '', '', '');
		}
		
		return $this->_client;
	}
	
}