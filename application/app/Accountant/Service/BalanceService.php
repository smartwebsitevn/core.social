<?php namespace App\Accountant\Service;

use App\Accountant\Job\ChangeBalance;
use App\Accountant\Model\LogBalanceModel as LogBalanceModel;
use App\Accountant\Library\Reason;
use App\Purse\Model\PurseModel as PurseModel;
use App\User\Model\UserModel;

class BalanceService
{
	/**
	 * Nap tien
	 *
	 * @param PurseModel $purse
	 * @param float      $purse_amount 	So tien tinh theo don vi tien te cua purse
	 * @param Reason     $reason
	 * @param array      $data
	 * @return LogBalanceModel
	 */
	public function add(PurseModel $purse, $purse_amount, Reason $reason, array $data = [])
	{
		return (new ChangeBalance('+', $purse, $purse_amount, $reason, $data))->handle();
	}

	/**
	 * Rut tien
	 *
	 * @param PurseModel $purse
	 * @param float      $purse_amount 	So tien tinh theo don vi tien te cua purse
	 * @param Reason     $reason
	 * @param array      $data
	 * @return LogBalanceModel
	 */
	public function sub(PurseModel $purse, $purse_amount, Reason $reason, array $data = [])
	{
		return (new ChangeBalance('-', $purse, $purse_amount, $reason, $data))->handle();
	}

	/**
	 * Gan balance
	 *
	 * @param float $value
	 */
	public function setBalance($value)
	{
		model('setting')->set('accountant-balance', $value);
	}

	/**
	 * Lay balance
	 *
	 * @return float
	 */
	public function getBalance()
	{
		return (float) model('setting')->get('accountant-balance');
	}

	/**
	 * Tao ChangeBalanceReason
	 *
	 * @param string $key
	 * @param array  $options
	 * @return Reason
	 */
	public function makeReason($key, array $options)
	{
		$class = 'App\Accountant\ChangeBalanceReason\\'.$key;

		return new $class($options);
	}

	/**
	 * Lay tong so tien bi tru cua user trong ngay hom nay (tinh theo tien te mac dinh)
	 *
	 * @param UserModel $user
	 * @return float
	 */
	public function totalSubAmountOfUserInToday(UserModel $user)
	{
		$range = get_time_between(get_date());

		$result = t('db')->select_sum('amount')->where([
			'status'     => '-',
			'user_id'    => $user->id,
			'created >=' => $range[0],
			'created <'  => $range[1],
		])->get('log_balance')->row();

		return (float) $result->amount;
	}

	/**
	 * Lay so du cuoi cua purse
	 *
	 * @param PurseModel $purse
	 * @return float
	 */
	public function getLastBalanceOfPurse(PurseModel $purse)
	{
		$list = (new LogBalanceModel())->newQuery()->get_list([
			'where' => ['purse_id' => $purse->id],
			'order' => ['id', 'desc'],
			'limit' => [0, 1],
		]);

		$list = LogBalanceModel::makeCollection($list);

		return $list->count() ? $list->first()->purse_balance : 0;
	}

}