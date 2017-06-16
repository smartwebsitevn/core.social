<?php namespace App\Accountant\Job;

use App\Accountant\AccountantFactory as AccountantFactory;
use App\Accountant\Model\LogStockModel as LogStockModel;
use App\Accountant\Model\LogLedgerModel as LogLedgerModel;
use App\Accountant\Library\Reason;

class ChangeStock extends \Core\Base\Job
{
	/**
	 * Trang thai thay doi (+ || -)
	 *
	 * @var string
	 */
	protected $status;

	/**
	 * So tien thay doi
	 *
	 * @var float
	 */
	protected $amount;

	/**
	 * Ly do thuc hien
	 *
	 * @var Reason
	 */
	protected $reason;

	/**
	 * Du lieu bo sung
	 *
	 * @var array
	 */
	protected $data;


	/**
	 * Khoi tao doi tuong
	 *
	 * @param string $status
	 * @param float  $amount
	 * @param Reason $reason
	 * @param array  $data
	 */
	public function __construct($status, $amount, Reason $reason, array $data = [])
	{
		$this->status = $status;
		$this->amount = $amount;
		$this->reason = $reason;
		$this->data = $data;
	}

	/**
	 * Thuc hien xu ly
	 *
	 * @return LogStockModel
	 */
	public function handle()
	{
		$inventory = $this->changeInventory();

		return $this->createLogStock($inventory);
	}

	/**
	 * Thay doi inventory
	 *
	 * @return float
	 */
	protected function changeInventory()
	{
		$inventory = AccountantFactory::stock()->getInventory();

		$inventory = ($this->status == '+')
			? $inventory + $this->amount
			: $inventory - $this->amount;

		AccountantFactory::stock()->setInventory($inventory);

		return $inventory;
	}

	/**
	 * Tao LogStock
	 *
	 * @param $inventory
	 * @return LogStockModel
	 */
	protected function createLogStock($inventory)
	{
		$data = array_merge($this->data, [
			'status'         => $this->status,
			'amount'         => $this->amount,
			'reason_key'     => $this->reason->key(),
			'reason_options' => $this->reason->options(),
			'desc'           => $this->reason->desc(),
			'inventory'         => $inventory,
		]);

		$log_stock = new LogStockModel($data);

//		$this->createLogLedger($log_stock);

		return $log_stock;
	}

	/**
	 * Tao LogLedger
	 *
	 * @param LogStockModel $log_stock
	 * @return LogLedgerModel
	 */
	protected function createLogLedger(LogStockModel $log_stock)
	{
		$data = $log_stock->onlyAttributes(['status', 'amount', 'desc', 'inventory']);

		$data = array_merge($data, [
			'type'    => 'stock',
			'options' => $log_stock->onlyAttributes(['reason_key', 'reason_options']),
		]);

		return AccountantFactory::ledger()->log($data);
	}

}