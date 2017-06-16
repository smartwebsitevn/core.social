<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Deposit_sms_cronjob
{
	public function handle()
	{
		lib('queue_session')->run('deposit_sms', 30, 2);
	}
}