<?php namespace App\User;

use App\User\Service\UserAuth;
use App\User\Service\UserGroupService;
use App\User\Service\UserService;

class UserFactory extends \Core\Base\Factory
{
	/**
	 * Lay namespace root
	 *
	 * @return string
	 */
	public function getRootNamespace()
	{
		return __NAMESPACE__;
	}

	/**
	 * Lay doi tuong UserAuth
	 *
	 * @return UserAuth
	 */
	public static function auth()
	{
		return static::makeService('UserAuth');
	}

	/**
	 * Lay doi tuong UserGroupService
	 *
	 * @return UserGroupService
	 */
	public static function userGroup()
	{
		return static::makeService('UserGroupService');
	}

	/**
	 * Lay doi tuong UserService
	 *
	 * @return UserService
	 */
	public static function user()
	{
		return static::makeService('UserService');
	}

}