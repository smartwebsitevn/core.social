<?php namespace App\Accountant\Service;

use App\Accountant\AccountantFactory as AccountantFactory;
use App\Accountant\Model\LogLedgerModel as LogLedgerModel;

class LedgerService
{
	/**
	 * Tao LogLedger
	 *
	 * @param array $data
	 * @return LogLedgerModel
	 */
	public function log(array $data)
	{
		$profit = array_get($data, 'profit', function()
		{
			return AccountantFactory::cash()->getProfit();
		});

		$inventory = array_get($data, 'inventory', function()
		{
			AccountantFactory::stock()->getInventory();
		});

		$balance = array_get($data, 'balance', function()
		{
			AccountantFactory::balance()->getBalance();
		});

		$total_assets = $this->makeTotalAssets($profit, $inventory, $balance);

		$this->setTotalAssets($total_assets);

		return LogLedgerModel::create(array_merge($data, compact(
			'profit', 'inventory', 'balance', 'total_assets'
		)));
	}

	/**
	 * Tinh total_assets
	 *
	 * @param float $profit
	 * @param float $inventory
	 * @param float $balance
	 * @return float
	 */
	public function makeTotalAssets($profit, $inventory, $balance)
	{
		return $profit + $inventory - $balance;
	}

	/**
	 * Gan total_assets
	 *
	 * @param float $value
	 */
	protected function setTotalAssets($value)
	{
		model('setting')->set('accountant-total_assets', $value);
	}

	/**
	 * Lay total_assets
	 *
	 * @return float
	 */
	public function getTotalAssets()
	{
		return (float) setting_get('accountant-total_assets');
	}

}