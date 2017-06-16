<?php namespace App\Purse\Job;

use App\Currency\Model\CurrencyModel;
use App\Purse\PurseFactory as PurseFactory;
use App\Purse\Model\PurseModel as PurseModel;
use App\Currency\CurrencyFactory as CurrencyFactory;
use App\User\Model\UserModel;
use TF\Support\Collection;

class ListUserPurses extends \Core\Base\Job
{
	/**
	 * Thong tin user
	 *
	 * @var UserModel
	 */
	protected $user;

	/**
	 * Danh sach purses cua user trong db
	 *
	 * @var array
	 */
	protected $purses;


	/**
	 * Khoi tao doi tuong
	 *
	 * @param UserModel $user
	 */
	public function __construct(UserModel $user)
	{
		$this->user = $user;
	}

	/**
	 * Thuc hien xu ly
	 *
	 * @return Collection
	 */
	public function handle()
	{
		$result = [];

		foreach ($this->listCurrencies() as $currency)
		{
			$purse = $this->findPurse($currency) ?: $this->createPurse($currency);

			if ($purse)
			{
			    $result[] = $purse;
			}
		}

		return collect($result);
	}

	/**
	 * Lay danh sach Currency
	 *
	 * @return Collection
	 */
	protected function listCurrencies()
	{
		return CurrencyFactory::currency()->lists();
	}

	/**
	 * Tim purse
	 *
	 * @param CurrencyModel $currency
	 * @return PurseModel|null
	 */
	protected function findPurse(CurrencyModel $currency)
	{
		return collect($this->getPurses())
			->whereLoose('currency_id', $currency->id)
			->first();
	}

	/**
	 * Tao purse
	 *
	 * @param CurrencyModel $currency
	 * @return PurseModel|null
	 */
	protected function createPurse(CurrencyModel $currency)
	{
		return PurseFactory::purse()->create($this->user, $currency);
	}

	/**
	 * Lay danh sach purses cua user trong db
	 *
	 * @return array
	 */
	protected function getPurses()
	{
		if (is_null($this->purses))
		{
			$user_id = $this->user->id;

			$purses = (new PurseModel)->newQuery()->get_list([
				'where' => compact('user_id'),
			]);

			return PurseModel::makeCollection($purses);
		}

		return $this->purses;
	}

}