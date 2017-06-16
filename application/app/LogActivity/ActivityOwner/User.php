<?php namespace App\LogActivity\ActivityOwner;

use App\LogActivity\Library\ActivityOwner;
use App\User\UserFactory as UserFactory;
use App\User\Model\UserModel as UserModel;

class User extends ActivityOwner
{
	/**
	 * Doi tuong UserModel
	 *
	 * @var UserModel
	 */
	protected $user;

	/**
	 * Khoi tao doi tuong
	 *
	 * @param UserModel $user
	 */
	public function __construct(UserModel $user = null)
	{
		if (is_null($user))
		{
			$user = UserFactory::auth()->user();
		}

		$this->user = $user;

		parent::__construct('user', $user->getKey(), $user->toArray());
	}

}