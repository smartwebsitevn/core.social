<?php namespace App\Accountant\Service;

use App\Accountant\Job\ChangeStock;
use App\Accountant\Model\LogStockModel as LogStockModel;
use App\Accountant\Library\Reason;

class StockService
{
	public static function _t()
	{
		$me = new static;

		$v = $me->makeReason('Other', ['desc' => __METHOD__]);

		pr($v, 0);
	}

	/**
	 * Nhap kho
	 *
	 * @param float  $amount
	 * @param Reason $reason
	 * @param array  $data
	 * @return LogStockModel
	 */
	public function add($amount, Reason $reason, array $data = [])
	{
		return (new ChangeStock('+', $amount, $reason, $data))->handle();
	}

	/**
	 * Xuat kho
	 *
	 * @param float  $amount
	 * @param Reason $reason
	 * @param array  $data
	 * @return LogStockModel
	 */
	public function sub($amount, Reason $reason, array $data = [])
	{
		return (new ChangeStock('-', $amount, $reason, $data))->handle();
	}

	/**
	 * Gan inventory
	 *
	 * @param float $value
	 */
	public function setInventory($value)
	{
		model('setting')->set('accountant-inventory', $value);
	}

	/**
	 * Lay inventory
	 *
	 * @return float
	 */
	public function getInventory()
	{
		return (float) model('setting')->get('accountant-inventory');
	}

	/**
	 * Tao ChangeStockReason
	 *
	 * @param string $key
	 * @param array  $options
	 * @return Reason
	 */
	public function makeReason($key, array $options)
	{
		$class = 'App\Accountant\ChangeStockReason\\'.$key;

		return new $class($options);
	}

}