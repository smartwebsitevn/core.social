<?php namespace App\Accountant\Job;

use App\Accountant\AccountantFactory as AccountantFactory;
use App\Accountant\Model\LogCashModel as LogCashModel;
use App\Accountant\Model\LogLedgerModel as LogLedgerModel;
use App\Accountant\Library\Reason;

class ChangeCash extends \Core\Base\Job
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
	 * @return LogCashModel
	 */
	public function handle()
	{
		$profit = $this->changeProfit();

		return $this->createLogCash($profit);
	}

	/**
	 * Thay doi profit
	 *
	 * @return float
	 */
	protected function changeProfit()
	{
		$profit = AccountantFactory::cash()->getProfit();

		$profit = ($this->status == '+')
			? $profit + $this->amount
			: $profit - $this->amount;

		AccountantFactory::cash()->setProfit($profit);

		return $profit;
	}

	/**
	 * Tao LogCash
	 *
	 * @param $profit
	 * @return LogCashModel
	 */
	protected function createLogCash($profit)
	{
		$data = array_merge($this->data, [
			'status'         => $this->status,
			'amount'         => $this->amount,
			'reason_key'     => $this->reason->key(),
			'reason_options' => $this->reason->options(),
			'desc'           => $this->reason->desc(),
			'profit'         => $profit,
		]);

		$log_cash = LogCashModel::create($data);

//		$this->createLogLedger($log_cash);

		return $log_cash;
	}

	/**
	 * Tao LogLedger
	 *
	 * @param LogCashModel $log_cash
	 * @return LogLedgerModel
	 */
	protected function createLogLedger(LogCashModel $log_cash)
	{
		$data = $log_cash->onlyAttributes(['status', 'amount', 'desc', 'profit']);

		$data = array_merge($data, [
			'type'    => 'cash',
			'options' => $log_cash->onlyAttributes(['reason_key', 'reason_options']),
		]);

		return AccountantFactory::ledger()->log($data);
	}

}