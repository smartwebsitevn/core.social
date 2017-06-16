<?php namespace App\User\Model;

use App\Purse\Model\PurseModel;
use TF\Support\Collection;
use App\User\UserFactory as UserFactory;
use App\User\Model\UserGroupModel as UserGroupModel;
use App\Purse\PurseFactory as PurseFactory;

class UserModel extends \Core\Base\Model
{
	protected $table = 'user';


	/**
	 * Tao doi tuong moi va gan $attributes
	 *
	 * @param array $attributes
	 * @return static
	 */
	public static function newWithAttributes(array $attributes)
	{
		$relations = static::pullRelationsFromAttributes($attributes, [

			'user_group' => 'App\User\Model\UserGroupModel',

			'purses' => [
				'type'  => 'many',
				'class' => 'App\Purse\Model\PurseModel',
			],

		]);

		return parent::newWithAttributes($attributes)->fill($relations);
	}

	/**
	 * Gan user_group
	 *
	 * @param UserGroupModel $user_group
	 */
	protected function setUserGroupAttribute(UserGroupModel $user_group)
	{
		$this->additional['user_group'] = $user_group;
	}

	/**
	 * Lay user_group
	 *
	 * @return UserGroupModel|null
	 */
	protected function getUserGroupAttribute()
	{
		$user_group_id = $this->getAttribute('user_group_id');

		return $this->isGuest()
			? UserFactory::userGroup()->getForGuest()
			: UserFactory::userGroup()->find($user_group_id);
	}

	/**
	 * Gan purses
	 *
	 * @param Collection $purses
	 */
	protected function setPursesAttribute(Collection $purses)
	{
		$this->additional['purses'] = $purses;
	}

	/**
	 * Lay purses
	 *
	 * @return Collection
	 */
	protected function getPursesAttribute()
	{
		if ( ! array_key_exists('purses', $this->additional))
		{
			$user_id = $this->getKey();

			$this->additional['purses'] = PurseFactory::purse()->userPurses($this);
		}

		return $this->additional['purses'];
	}

	/**
	 * Lay purse_default
	 *
	 * @return PurseModel
	 */
	protected function getPurseDefaultAttribute()
	{
		$purses = $this->getAttribute('purses');

		return $purses->first(function($i, PurseModel $purse)
		{
			return $purse->currency->is_default;
		});
	}

	/**
	 * Tao admin url
	 *
	 * @param string $action
	 * @param array  $opt
	 * @return string
	 */
	public function adminUrl($action, array $opt = [])
	{
		switch ($action)
		{
			case 'view':
			{
				return admin_url($this->getTable(), $opt).'?id='.$this->getKey();
			}
		}

		return parent::adminUrl($action, $opt);
	}

	/**
	 * Kiem tra user co phai la khach hay khong
	 *
	 * @return bool
	 */
	public function isGuest()
	{
		return ! $this->getKey();
	}
}