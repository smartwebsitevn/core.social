<?php namespace App\Purse\Job;

use App\Purse\Model\PurseModel;
use App\User\Model\UserModel;

class ChangePurseBalance extends \Core\Base\Job
{
	/**
	 * Doi tuong Purse
	 *
	 * @var PurseModel
	 */
	protected $purse;

	/**
	 * So luong balance thay doi
	 *
	 * @var float
	 */
	protected $amount;

	/**
	 * Trang thai thay doi
	 *
	 * @var string
	 */
	protected $status;

	/**
	 * Balance truoc thay doi
	 *
	 * @var float
	 */
	protected $balance_before;

	/**
	 * Balance sau thay doi
	 *
	 * @var float
	 */
	protected $balance_after;


	/**
	 * Khoi tao doi tuong
	 *
	 * @param PurseModel  $purse
	 * @param float  $amount	So tien tinh theo don vi tien te cua purse
	 * @param string $status	'+' || '-'
	 */
	public function __construct(PurseModel $purse, $amount, $status)
	{
		$this->purse = $purse;
		$this->amount = $amount;
		$this->status = $status;
	}

	/**
	 * Thuc hien xu ly
	 *
	 * @return PurseModel
	 */
	public function handle()
	{
		if ( ! $this->amount) return $this->purse;

		$balance_before = $this->purse->balance;

		$this->updatePurseBalance();

		$this->verifyBalanceChange($balance_before);

		return $this->purse;
	}

	/**
	 * Thuc hien update balance cua purse
	 */
	protected function updatePurseBalance()
	{
		$this->purse->updateBalance($this->getBalanceAfter());
	}

	/**
	 * Lay balance truoc thay doi
	 *
	 * @return float
	 */
	public function getBalanceBefore()
	{
		if (is_null($this->balance_before))
		{
			$this->balance_before = PurseModel::find($this->purse->id)->balance;
		}

		return $this->balance_before;
	}

	/**
	 * Lay balance sau thay doi
	 *
	 * @return float
	 */
	public function getBalanceAfter()
	{
		if (is_null($this->balance_after))
		{
			$balance_before = $this->getBalanceBefore();

			$this->balance_after = $this->makeBalanceAfter($balance_before);
		}

		return $this->balance_after;
	}

	/**
	 * Tinh so tien sau thay doi
	 *
	 * @param float $balance_before
	 * @return float
	 */
	protected function makeBalanceAfter($balance_before)
	{
		return ($this->status == '+')
			? $balance_before + $this->amount
			: $balance_before - $this->amount;
	}

	/**
	 * Kiem tra so du truoc va sau giao dich
	 *
	 * @param float $balance_before
	 */
	protected function verifyBalanceChange($balance_before)
	{
		$balance_cur = $this->purse->balance;

		$balance_after = $this->makeBalanceAfter($balance_before);

		if ($balance_cur < 0)
		{
			return $this->blockUser($this->purse->user, "Balance current invalid {$balance_cur} < 0");
		}

		if (floor($balance_cur) != floor($balance_after))
		{
			return $this->blockUser($this->purse->user, "Balance change error {$balance_cur} != {$balance_after}");
		}
	}

	/**
	 * Thuc hien block user
	 *
	 * @param UserModel $user
	 * @param string    $reason
	 */
	protected function blockUser(UserModel $user, $reason)
	{
		$user->update(['blocked' => '1']);

		model('log_system')->add("Block user #{$user->id} ($user->username). Reason: {$reason}");
	}

	/**
	 * Lay Purse
	 *
	 * @return PurseModel
	 */
	public function getPurse()
	{
		return $this->purse;
	}

	/**
	 * Lay amount
	 *
	 * @return float
	 */
	public function getAmount()
	{
		return $this->amount;
	}

	/**
	 * Lay status
	 *
	 * @return mixed
	 */
	public function getStatus()
	{
		return $this->status;
	}

}