<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Deposit_sms_gateway_handler
{
	/**
	 * Khoi tao doi tuong
	 */
	public function __construct()
	{
		t('lang')->load('sms_gateway_handler/deposit');
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

		$args 		= explode(' ', array_get($sms, 'param'));
		$amount 	= (int) array_get($args, 0);
		$username 	= array_get($args, 1);
		
		if ($amount < 1 || $amount > 100)
		{
			return lang('amount_invalid');
		}
		
		$user = model('user')->get_info_rule(compact('username'));
		if ( ! $user)
		{
			return lang('user_not_exist');
		}
		

		$amount *= 1000;
		
		$this->perform_deposit($amount, $user, $sms);
		
		return lang(
			'deposit_success', 
			$user->username ?: $user->email, 
			currency_format_amount_default($amount, false)
		);
	}
	
	/**
	 * Thuc hien nap tien cho user
	 * 
	 * @param float $amount
	 * @param object $user
	 * @param array $sms
	 */
	protected function perform_deposit($amount, $user, array $sms)
	{
		$fee = $this->_mod()->get_fee($amount, $user->user_group_id);
		
		$amount_deposit = $amount - $fee;
		
		mod('queue')->push('deposit_sms', [$user->id, $amount_deposit, $sms]);
		
		//$this->_mod()->deposit($user->id, $amount_deposit, $sms);
	}

	/**
	 * Lay doi tuong xu ly cua mod
	 */
	protected function _mod()
	{
		return mod('deposit_sms');
	}
	
}