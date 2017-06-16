<?php namespace App\Deposit\Job;

use App\Accountant\AccountantFactory as AccountantFactory;
use App\Accountant\ChangeBalanceReason\Deposit as DepositReason;
use App\Deposit\DepositFactory as DepositFactory;
use App\Deposit\Model\DepositModel as DepositModel;
use App\LogActivity\Model\LogActivityModel as LogActivityModel;

class ActiveDepositPayment extends \Core\Base\Job
{
	/**
	 * Doi tuong DepositModel
	 *
	 * @var DepositModel
	 */
	protected $deposit;

	/**
	 * Options
	 *
	 * @var array
	 */
	protected $options;


	/**
	 * Khoi tao doi tuong
	 *
	 * @param DepositModel $deposit
	 * @param array        $options
	 * 		$options = [
	 * 			'owner' => null,
	 * 		];
	 */
	public function __construct(DepositModel $deposit, array $options = [])
	{
		$this->deposit = $deposit;
		$this->options = $options;
	}

	/**
	 * Thuc hien xu ly
	 */
	public function handle()
	{
		$this->logActivity('active');

		$this->addPurseBalance();

		DepositFactory::deposit()->complete($this->deposit, $this->options);
	}

	/**
	 * Thuc hien cong tien cho purse
	 */
	protected function addPurseBalance()
	{
		$purse = $this->deposit->purse;

		$purse_amount = $this->deposit->amount;

		$reason = DepositReason::make($this->deposit->invoice_order);

		AccountantFactory::balance()->add($purse, $purse_amount, $reason);
	}

	/**
	 * Log activity
	 *
	 * @param string $action
	 * @return LogActivityModel
	 */
	protected function logActivity($action)
	{
		return DepositFactory::deposit()->logActivity($action, $this->deposit, $this->getOption('owner'));
	}

	/**
	 * Lay option
	 *
	 * @param string $key
	 * @param mixed  $default
	 * @return mixed
	 */
	public function getOption($key = null, $default = null)
	{
		return array_get($this->options, $key, $default);
	}
}