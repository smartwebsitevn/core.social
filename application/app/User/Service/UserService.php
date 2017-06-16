<?php namespace App\User\Service;

use App\User\Model\UserModel;
use Core\Support\Arr;

class UserService
{
	/**
	 * Lay thong tin thanh vien tu key
	 *
	 * @param string $key
	 * @return null|UserModel
	 */
	public function find($key)
	{
		$user = model('user')->find_user($key);

		return $user ? UserModel::newWithAttributes(Arr::toArray($user)) : null;
	}
}