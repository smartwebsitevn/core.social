<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Payment_card_mod extends MY_Mod
{
	/**
	 * Kiem tra ton tai
	 * 
	 * @param string $key
	 * @return boolean
	 */
	public function exists($key)
	{
		if ( ! $key) return false;
		
		$url = APPPATH.'libraries/payment_card/'.$key.'_payment_card'.EXT;
		
		return file_exists($url);
	}
	
	/**
	 * Kiem tra da duoc cai dat hay chua
	 * 
	 * @param string $key
	 * @return boolean
	 */
	public function installed($key)
	{
		if ( ! $key) return FALSE;
		
		$list_installed = model('payment_card')->get_list_installed();
		
		return (in_array($key, $list_installed));
	}
	
}