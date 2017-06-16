<?php

use App\Currency\Model\CurrencyModel;
use App\Deposit\Command\DepositCardCommand;
use App\Deposit\Job\DepositCard;
use App\Invoice\Model\InvoiceOrderModel;
use App\Purse\PurseFactory;
use App\User\Model\UserModel;

class Deposit_card_mod extends MY_Mod
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
		$setting = $setting ?: array();
		
		$setting = array_add($setting, 'fail_count_max', 10);
		$setting = array_add($setting, 'fail_block_timeout', 60);
		
		$setting['offline_providers'] = $this->get_table('offline_providers');
		
		return array_get($setting, $key, $default);
	}
	
	/**
	 * Lay thong tin cua cac table
	 * 
	 * @param string $table
	 * @param array $order
	 * @return array
	 */
	public function get_table($table, $order = array('order', 'asc'))
	{
		$tbl = model('module')->table_get_db_name($this->_get_mod(), $table);
		
		return model('db')->get($tbl, $order);
	}
	
	/**
	 * Lay row cua table
	 * 
	 * @param string $table
	 * @param string $id
	 * @return false|object
	 */
	public function get_row($table, $id)
	{
		$tbl = model('module')->table_get_db_name($this->_get_mod(), $table);
		
		return model('db')->row($tbl, $id);
	}

}