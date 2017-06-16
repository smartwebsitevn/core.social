<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Deposit_banking_mod extends MY_Mod
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
		$setting = setting_get_group('config-deposit');
		
		foreach (array('amount_min', 'amount_max') as $p)
		{
			$setting[$p] = max(0, (float) $setting[$p]);
		}
		
		return array_get($setting, $key, $default);
	}
	
	/**
	 * Kiem tra amount
	 * 
	 * @param float $amount
	 * @return boolean
	 */
	public function valid_amount($amount)
	{
		return valid_amount($amount, $this->setting_amount());
	}
	
	/**
	 * Lay setting amount
	 * 
	 * @return array
	 */
	public function setting_amount()
	{
		$setting = array();
		foreach (array('min', 'max') as $p)
		{
			$setting[$p] = $this->setting('amount_'.$p);
		}
		
		return $setting;
	}
	
	/**
	 * Xu ly tao giao dich deposit_banking
	 * 
	 * @param array $input	Thong tin deposit:
	 * 	int		'user_id'
	 * 	int		'bank_id'
	 * 	int		'acc_id'
	 * 	int		'acc_name'
	 * 	float	'amount'
	 * 	string	'desc' 			= ''
	 * @param array $output
	 * @return int $tran_id
	 */
	public function create(array $input, &$output = array())
	{
		$user_id 	= $input['user_id'];
		$bank_id 	= $input['bank_id'];
		$acc_id 	= $input['acc_id'];
		$acc_name 	= $input['acc_name'];
		$amount 	= $input['amount'];
		$desc 		= array_get($input, 'desc', '');
		
		$bank = model('bank')->get_info($bank_id);
		
		$fee = mod('bank')->get_fee($bank_id, $amount);
		
		$amount_pay = $amount + $fee;
		
		
		$tran = array();
		$tran_id = mod('tran')->create(array(
			'type' 			=> 'deposit',
			'amount' 		=> $amount,
			'user_id' 		=> $user_id,
			'payment' 		=> 'banking',
			'payment_amount' 	=> $amount_pay,
			'payment_currency' 	=> serialize(currency_get_default()),
		), $tran);
		
		
		$order_deposit = array(
			'tran_id' 	=> $tran_id,
			'status' 	=> mod('order')->status('pending'),
		);
		model('order_deposit')->create($order_deposit);
		
		
		$tran_banking = array(
			'bank_id' 			=> $bank->id,
			'bank_name' 		=> $bank->name,
			'sender_acc_id' 	=> $acc_id,
			'sender_acc_name' 	=> $acc_name,
			'receiver_acc_id' 	=> $bank->acc_id,
			'receiver_acc_name' => $bank->acc_name,
			'amount' 			=> $amount_pay,
			'content_transfer' 	=> mod('tran_banking')->make_content_transfer($tran_id),
			'desc' 				=> $desc,
		);
		model('tran_banking')->set($tran_id, $tran_banking);
		
		
		$output = compact('tran', 'order_deposit', 'tran_banking');
		
		return $tran_id;
	}
	
}