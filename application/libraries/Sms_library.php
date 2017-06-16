<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Sms Library Class
 * 
 * @author		sontung0@gmail.com
 * @version		2015-04-03
 */

// --------------------------------------------------------------------

/**
 * Class de goi cac class thanh phan
 */
class Sms_library
{
	/**
	 * Goi cac class duoc yeu cau
	 */
	public function __get($key)
	{
		return t('lib')->driver('sms', $key);
	}
	
	/**
	 * Goi method cua cac class
	 * 
	 * @param string $method
	 * @param array  $args
	 * @param string $type		Kieu cua nha cung cap (send|receive)
	 * @return mixed
	 */
	public function call($method, array $args = array(), $type = 'send')
	{
		$provider = $this->get_provider($type);
		
		return call_user_func_array(array($this->{$provider}, $method), $args);
	}
	
	/**
	 * Lay nha cung cap hien tai
	 * 
	 * @param string $type		Kieu cua nha cung cap (send|receive)
	 * @return string
	 */
	public function get_provider($type = 'send')
	{
		return mod('sms')->config('provider_'.$type);
	}
	
	/**
	 * Xu ly phone
	 * 
	 * @param string $phone
	 * @param string $type
	 * 		'short'		0123456789
	 * 		'full'		84123456789
	 * 		'full+'		+84123456789
	 * @return string
	 */
	public function make_phone($phone, $type = 'full')
	{
		$phone = '+' . ltrim($phone, '+');
		$phone = preg_replace('#^\+0#', '+84', $phone);
		
		if ($type == 'short')
		{
			$phone = preg_replace('#^\+84#', '0', $phone);
		}
		elseif ($type == 'full')
		{
			$phone = ltrim($phone, '+');
		}
		
		return $phone;
	}

	/**
	 * Gui sms
	 *
	 * @see MY_Sms::send()
	 */
	public function send($phone, $msg, $sender = NULL)
	{
		$phone 	= $this->make_phone($phone, 'full');
		$sender = (is_null($sender)) ? mod('sms')->config('sender') : $sender;
		
		return $this->call(__FUNCTION__, array($phone, $msg, $sender), 'send');
	}
	
	/**
	 * Lay so du cua merchant
	 *
	 * @see MY_Sms::get_balance()
	 */
	public function get_balance()
	{
		return $this->call(__FUNCTION__, func_get_args(), 'send');
	}
	
	/**
	 * Lay danh sach ip cua service
	 *
	 * @see MY_Sms::get_service_ip()
	 */
	public function get_service_ip()
	{
		return $this->call(__FUNCTION__, func_get_args(), 'receive');
	}
	
	/**
	 * Kiem tra ket noi xem co hop le hay khong
	 *
	 * @see MY_Sms::check_request()
	 */
	public function check_request()
	{
		return $this->call(__FUNCTION__, func_get_args(), 'receive');
	}
	
	/**
	 * Lay input khi nhan thong tin tu service
	 *
	 * @see MY_Sms::get_input_receive()
	 */
	public function get_input_receive($param = NULL)
	{
		return $this->call(__FUNCTION__, func_get_args(), 'receive');
	}

	/**
	 * Gui phan hoi sau khi nhan yeu cau tu service
	 *
	 * @see MY_Sms::make_feedback()
	 */
	public function make_feedback($content)
	{
		return $this->call(__FUNCTION__, func_get_args(), 'receive');
	}
	
}


/**
 * Class xay dung cua cac class thanh phan
 */
Class MY_Sms {
	
	// Bien luu thong tin gui den view
	var $data = array();
	
	/**
	 * Cho phep su dung cac thuoc tinh cua controller
	 */
	public function __get($key)
	{
		$CI =& get_instance();
		return $CI->$key;
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
		return TRUE;
	}
	
	/**
	 * Lay so du cua merchant
	 * 
	 * @return float
	 */
	public function get_balance()
	{
		return 0;
	}
	
	/**
	 * Lay danh sach ip cua service
	 * 
	 * @return
	 * 		array()	= Thuc hien kiem tra ip
	 * 		NULL	= Khong thuc hien kiem tra
	 */
	public function get_service_ip()
	{
		return array();
	}
	
	/**
	 * Kiem tra ket noi xem co hop le hay khong
	 * 
	 * @return boolean
	 */
	public function check_request()
	{
		return TRUE;
	}
	
	/**
	 * Lay input khi nhan thong tin tu service
	 * 
	 * @param string $param		Bien muon lay (sms_id|message|port|phone)
	 * @return mixed
	 */
	public function get_input_receive($param = NULL){}
	
	/**
	 * Gui phan hoi sau khi nhan yeu cau tu service
	 * 
	 * @param string $content
	 */
	public function make_feedback($content){}
	
}
