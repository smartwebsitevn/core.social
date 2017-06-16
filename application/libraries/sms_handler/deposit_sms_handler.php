<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Deposit_sms_handler
{
	/**
	 * Khoi tao doi tuong
	 */
	public function __construct()
	{
		t('lang')->load('site/deposit_sms');
	}
	
	/**
	 * Xu ly sms
	 * 
	 * @param array $sms
	 * @return string
	 */
	public function handle(array $sms)
	{
		if ( ! $this->_mod()->setting('status'))
		{
			return lang('deposit_sms_off');
		}
		
		$user_id = (int) array_get($sms, 'param');
		$user = model('user')->get_info($user_id);
		if ( ! $user)
		{
			return lang('user_not_exist');
		}
		
		$port 	= array_get($sms, 'port');
		$amount = $this->_mod()->get_amount($port);
		if ( ! $amount)
		{
			return lang('port_sms_invalid');
		}
		
		
		$price 	= (float) array_get($sms, 'price');
		$amount = min($amount, $price);
		
		$this->_mod()->deposit($user->id, $amount, $sms);
		
		return lang(
			'deposit_success', 
			$user->email, 
			currency_format_amount_default($amount, false)
		);
	}
	
	/**
	 * Lay doi tuong xu ly cua mod
	 */
	protected function _mod()
	{
		return mod('deposit_sms');
	}
	
}