<?php namespace App\Accountant\Job;

use App\Accountant\AccountantFactory as AccountantFactory;
use App\Accountant\Model\LogBalanceModel as LogBalanceModel;
use App\Accountant\Model\LogLedgerModel as LogLedgerModel;
use App\Accountant\Library\Reason;
use App\Purse\Job\ChangePurseBalance;
use App\Purse\Model\PurseModel as PurseModel;

class ChangeBalance extends \Core\Base\Job
{
	/**
	 * Trang thai thay doi (+ || -)
	 *
	 * @var string
	 */
	protected $status;

	/**
	 * Doi tuong PurseModel
	 *
	 * @var PurseModel
	 */
	protected $purse;

	/**
	 * So tien thay doi (tinh theo don vi tien te cua purse)
	 *
	 * @var float
	 */
	protected $purse_amount;

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
	 * @param string     $status
	 * @param PurseModel $purse
	 * @param float      $purse_amount
	 * @param Reason     $reason
	 * @param array      $data
	 */
	public function __construct($status, PurseModel $purse, $purse_amount, Reason $reason, array $data = [])
	{
		$this->status = $status;
		$this->purse = $purse;
		$this->purse_amount = $purse_amount;
		$this->reason = $reason;
		$this->data = $data;
	}

	/**
	 * Thuc hien xu ly
	 *
	 * @return LogBalanceModel
	 */
	public function handle()
	{
		$this->changePurseBalance();

		$balance = 0;//$this->changeBalance();

		return $this->createLogBalance($balance);
	}

	/**
	 * Thuc hien thay doi balance cua purse
	 *
	 * @return PurseModel
	 */
	protected function changePurseBalance()
	{
		return (new ChangePurseBalance(
			$this->purse, $this->purse_amount, $this->status
		))->handle();
	}

	/**
	 * Thay doi balance tong
	 *
	 * @return float
	 */
	protected function changeBalance()
	{
		$amount = $this->getAmount();

		$balance = AccountantFactory::balance()->getBalance();

		$balance = ($this->status == '+')
			? $balance + $amount
			: $balance - $amount;

		AccountantFactory::balance()->setBalance($balance);

		return $balance;
	}

	/**
	 * Tao LogBalance
	 *
	 * @param float $balance
	 * @return LogBalanceModel
	 */
	protected function createLogBalance($balance)
	{
		$data = array_merge($this->data, [
			'status'         => $this->status,
			'purse_id'       => $this->purse->id,
			'purse_amount'   => $this->purse_amount,
			'reason_key'     => $this->reason->key(),
			'reason_options' => $this->reason->options(),
			'amount'         => $this->getAmount(),
			'desc'           => $this->reason->desc(),
			'balance'        => $balance,
			'purse_balance'  => $this->purse->balance,
			'user_id'        => $this->purse->user_id,
			'currency_id'    => $this->purse->currency_id,
		]);

		$data = array_add($data, 'url', current_url(true));
		$data = array_add($data, 'ip', t('input')->ip_address());
		$data = array_add($data, 'user_agent', t('input')->user_agent());
		$data = array_add($data, 'referer', t('input')->server('HTTP_REFERER'));
		$data = array_add($data, 'session_id', session_id());

		$log_balance = LogBalanceModel::create($data);

//		$this->createLogLedger($log_balance);

		return $log_balance;
	}

	/**
	 * Tao LogLedger
	 *
	 * @param LogBalanceModel $log_balance
	 * @return LogLedgerModel
	 */
	protected function createLogLedger(LogBalanceModel $log_balance)
	{
		$data = $log_balance->onlyAttributes(['status', 'amount', 'desc', 'balance']);

		$data = array_merge($data, [
			'type'    => 'balance',
			'options' => $log_balance->onlyAttributes(['purse_id', 'purse_amount', 'reason_key', 'reason_options']),
		]);

		return AccountantFactory::ledger()->log($data);
	}

	/**
	 * Lay so tien thay doi tinh theo tien te mac dinh
	 *
	 * @return float
	 */
	protected function getAmount()
	{
		return currency_convert_amount_default($this->purse_amount, $this->purse->currency_id);
	}
}