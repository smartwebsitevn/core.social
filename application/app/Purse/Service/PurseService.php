<?php namespace App\Purse\Service;

use App\Currency\Model\CurrencyModel;
use App\User\UserFactory;
use TF\Support\Collection;
use App\Purse\Job\ListUserPurses;
use App\Purse\Model\PurseModel as PurseModel;
use App\User\Model\UserModel as UserModel;

class PurseService
{
	/**
	 * Tao purse
	 *
	 * @param UserModel     $user
	 * @param CurrencyModel $currency
	 * @return PurseModel|null
	 */
	public function create(UserModel $user, CurrencyModel $currency)
	{
		if ( ! $currency->can('create_purse')) return null;

		$purse = PurseModel::firstOrCreate([
			'user_id'     => $user->id,
			'currency_id' => $currency->id,
		]);

		$purse->update(['number' => strtoupper($currency->purse_prefix.$purse->id)]);

		return $purse;
	}

	/**
	 * Lay purse
	 *
	 * @param UserModel     $user
	 * @param CurrencyModel $currency
	 * @return PurseModel|null
	 */
	public function get(UserModel $user, CurrencyModel $currency)
	{
		return $this->create($user, $currency);
	}

	/**
	 * Lay danh sach purses cua user
	 *
	 * @param UserModel $user
	 * @return Collection
	 */
	public function userPurses(UserModel $user)
	{
		return (new ListUserPurses($user))->handle();
	}

	/**
	 * Lay thong tin purse tu key
	 *
	 * @param string $key
	 * @return null|PurseModel
	 */
	public function find($key)
	{
		if ( ! $key) return null;

		$query = t('db')->where('id', $key)
						->or_where('number', $key)
						->limit(1)
						->get('purse');

		return $query->num_rows()
			? PurseModel::newWithAttributes($query->row_array())
			: null;
	}

	/**
	 * Lay thong tin purse tu number
	 *
	 * @param string $number
	 * @return null|PurseModel
	 */
	public function findByNumber($number)
	{
		$number = strtoupper($number);

		return PurseModel::findWhere(compact('number'));
	}

	/**
	 * Lay thong tin purse tu user key
	 *
	 * @param string $key
	 * @return null|PurseModel
	 */
	public function findByUserKey($key)
	{
		$user = UserFactory::user()->find($key);

		return $user ? $user->purse_default : null;
	}

	/**
	 * Kiem tra user co the dung purse hay khong
	 *
	 * @param PurseModel $purse
	 * @param UserModel  $user
	 * @return bool
	 */
	public function canUseByUser(PurseModel $purse, UserModel $user)
	{
		return $purse->user_id == $user->id && ! $user->blocked;
	}
}