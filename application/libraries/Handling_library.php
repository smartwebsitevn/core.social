<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Thu vien xu ly cac doi tuong dang duoc xu ly
 * 
 * @author		***
 * @version		2014-07-25
 */
class Handling_library {
	
	/**
	 * Ham khoi dong
	 */
	function __construct()
	{
		t()->load->model('setting_model');
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Gan doi tuong dang xu ly
	 * @param string 	$name		Ten doi tuong
	 * @param mixed 	$value		Gia tri
	 */
	function set($name, $value)
	{
		$v = array();
		$v['value'] = $value;
		$v['time'] 	= now();
		
		t()->setting_model->set('handling-'.$name, $v);
	}
	
	/**
	 * Reset doi tuong dang xu ly
	 * @param string 	$name		Ten doi tuong
	 * @param mixed 	$value		Neu khai bao thi se kiem tra voi gia tri hien tai, neu giong thi moi reset
	 */
	function reset($name, $value = '')
	{
		if ($value && ! $this->check($name, $value))
		{
			return;
		}
		
		$this->set($name, '');
	}
	
	/**
	 * Lay doi tuong dang xu ly
	 * @param string 	$name		Ten doi tuong
	 * @param int 		$time		Thoi gian cap nhat gia tri
	 */
	function get($name, &$time = 0)
	{
		$v = t()->setting_model->get('handling-'.$name);
		
		$value 	= (isset($v['value'])) ? $v['value'] : '';
		$time 	= (isset($v['time'])) ? $v['time'] : 0;
		$time	= min($time, now());
		
		return $value;
	}
	
	/**
	 * Kiem tra co doi tuong dang xu ly hay khong
	 * @param string 	$name		Ten doi tuong
	 * @param int 		$time_out	Thoi gian het han
	 */
	function has($name, $time_out = 0)
	{
		// Lay doi tuong dang xu ly
		$time = '';
		$value = $this->get($name, $time);
		
		// Neu khong ton tai gia tri
		if ( ! $value)
		{
			return FALSE;
		}
		
		// Neu time qua time quy dinh
		$time_handle 	= now() - $time;
		$time_out 		= ( ! $time_out) ? 5*60 : $time_out;
		if ($time_handle > $time_out)
		{
			return FALSE;
		}
		
		return TRUE;
	}
	
	/**
	 * Kiem tra gia tri nay co phai gia tri cua doi duong dang xu ly hay khong
	 * @param string 	$name		Ten doi tuong
	 * @param mixed 	$value		Gia tri can kiem tra
	 */
	function check($name, $value)
	{
		$value_cur = $this->get($name);
		
		return ($value == $value_cur) ? TRUE : FALSE;
	}
	
}
