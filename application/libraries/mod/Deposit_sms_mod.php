<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Deposit_sms_mod extends MY_Mod
{
	/**
	 * Lay setting
	 * 
	 * @param string 	$key
	 * @param mixed 	$default
	 * @return mixed
	 */
	public function setting($key = null, $default = null)
	{
		$setting = module_get_setting($this->_get_mod());
		
		return array_get($setting, $key, $default);
	}
	
	/**
	 * Lay deposit amount tuong ung voi sms port
	 * 
	 * @param string $port
	 * @return float
	 */
	/* public function get_amount($port)
	{
		$num = substr($port, 1, 1);
		
		$amount = $this->setting('amount_'.$num);
		
		return max(0, (float) $amount);
	} */
	
	/**
	 * Lay fee
	 * 
	 * @param float $amount
	 * @return float
	 */
	public function get_fee($amount, $user_group_id = null)
	{
		if (is_null($user_group_id) && user_is_login())
		{
			$user_group_id = user_get_account_info()->user_group_id;
		}

		$percent = $user_group_id
					? (float) $this->setting('fee_percent_user_group_'.$user_group_id)
					: 0;
		
		$percent = $percent ?: (float) $this->setting('fee_percent');
		
		return get_fee($amount, compact('percent'));
	}
	
	/**
	 * Thuc hien nap tien cho user
	 * 
	 * @param int 	$user_id
	 * @param float $amount
	 * @param array $sms
	 * @return int
	 */
	public function deposit($user_id, $amount, array $sms)
	{
		$user_balance = model('user')->balance_plus($user_id, $amount);
		
		$tran_id = mod('tran')->create(array(
			'type' 			=> 'deposit',
			'amount' 		=> $amount,
			'status' 		=> 'completed',
			'payment' 		=> 'sms',
			'user_id' 		=> $user_id,
			'user_balance' 	=> $user_balance,
		));
		
		model('tran_payment')->set($tran_id, $sms);
		
		model('order_deposit')->create(array(
			'tran_id' 	=> $tran_id,
			'status' 	=> mod('order')->status('completed'),
		));
		
		return $tran_id;
	}
	
}