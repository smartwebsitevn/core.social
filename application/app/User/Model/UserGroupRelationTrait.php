<?php namespace App\User\Model;

trait UserGroupRelationTrait
{
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
		if ( ! array_key_exists('user_group', $this->additional))
		{
			$user_group_id = $this->getAttribute('user_group_id');

			$this->additional['user_group'] = UserGroupModel::find($user_group_id);
		}

		return $this->additional['user_group'];
	}

}