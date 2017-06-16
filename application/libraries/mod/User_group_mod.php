<?php

use App\User\Model\UserGroupModel;
use App\User\UserFactory;

class User_group_mod extends MY_Mod
{
	/**
	 * Lay nhom cua user hien tai
	 *
	 * @return UserGroupModel
	 */
	public function current()
	{
		return UserFactory::auth()->userGroup();
	}
}