<?php namespace App\User\Service;

use App\User\Model\UserModel as UserModel;
use App\User\Model\UserGroupModel as UserGroupModel;

class UserAuth
{
	/**
	 * Khoi tao doi tuong
	 */
	public function __construct()
	{
		t('load')->helper('user');
	}

	/**
	 * Lay thong tin user hien tai
	 *
	 * @return UserModel
	 */
	public function user($user = null)
	{
	    if($user == null)
	    {
	        $user = user_get_account_info();
	    }
	    
	    $user = $user ? (array) $user : [];
	    
		return UserModel::newWithAttributes($user);
	}

	/**
	 * Lay nhom cua user hien tai
	 *
	 * @return UserGroupModel
	 */
	public function userGroup()
	{
		return $this->user()->user_group;
	}

	/**
	 * Kiem tra user hien tai da login hay chua
	 *
	 * @return bool
	 */
	public function logged()
	{
		return $this->user()->id ? true : false;
	}

	/**
	 * Kiem tra quyen truy cap
	 *
	 * @param array $rules
	 *    $rules = [
	 * 		'user_id' => 0,
	 * 		'ip' => '',
	 *    ]
	 * @return bool
	 */
	public function checkAccess(array $rules)
	{
		$user_id = (int) $rules['user_id'];
		$ip = (string) $rules['ip'];

		if ($user_id)
		{
			return $user_id === (int) $this->user()->id;
		}

		return $ip === (string) t('input')->ip_address();
	}
}