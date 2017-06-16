<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Deposit_sms_queue_handler
{
	public function handle($user_id, $amount, array $sms)
	{
		mod('deposit_sms')->deposit($user_id, $amount, $sms);
	}
}