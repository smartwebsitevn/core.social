<?php namespace App\Accountant\Service;

use App\Accountant\Job\ChangeCash;
use App\Accountant\Model\LogCashModel as LogCashModel;
use App\Accountant\Library\Reason;

class CashService
{
	/**
	 * Thu
	 *
	 * @param float  $amount
	 * @param Reason $reason
	 * @param array  $data
	 * @return LogCashModel
	 */
	public function add($amount, Reason $reason, array $data = [])
	{
		return (new ChangeCash('+', $amount, $reason, $data))->handle();
	}

	/**
	 * Chi
	 *
	 * @param float  $amount
	 * @param Reason $reason
	 * @param array  $data
	 * @return LogCashModel
	 */
	public function sub($amount, Reason $reason, array $data = [])
	{
		return (new ChangeCash('-', $amount, $reason, $data))->handle();
	}

	/**
	 * Gan profit
	 *
	 * @param float $value
	 */
	public function setProfit($value)
	{
		model('setting')->set('accountant-profit', $value);
	}

	/**
	 * Lay profit
	 *
	 * @return float
	 */
	public function getProfit()
	{
		return (float) model('setting')->get('accountant-profit');
	}

	/**
	 * Tao ChangeCashReason
	 *
	 * @param string $key
	 * @param array  $options
	 * @return Reason
	 */
	public function makeReason($key, array $options)
	{
		$class = 'App\Accountant\ChangeCashReason\\'.$key;

		return new $class($options);
	}

}