<?php namespace App\User\Model;

trait UserRelationTrait
{
	/**
	 * Gan user
	 *
	 * @param UserModel $user
	 */
	protected function setUserAttribute(UserModel $user)
	{
		$this->additional['user'] = $user;
	}

	/**
	 * Lay user
	 *
	 * @return UserModel|null
	 */
	protected function getUserAttribute()
	{
		if ( ! array_key_exists('user', $this->additional))
		{
			$user_id = $this->getAttribute('user_id');

			$this->additional['user'] = UserModel::find($user_id);
		}

		return $this->additional['user'];
	}

}