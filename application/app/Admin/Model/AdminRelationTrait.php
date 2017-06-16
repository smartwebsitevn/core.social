<?php namespace App\Admin\Model;

use App\Admin\Model\AdminModel as AdminModel;

trait AdminRelationTrait
{
	/**
	 * Gan admin
	 *
	 * @param AdminModel $admin
	 */
	protected function setAdminAttribute(AdminModel $admin)
	{
		$this->additional['admin'] = $admin;
	}

	/**
	 * Lay admin
	 *
	 * @return AdminModel|null
	 */
	protected function getAdminAttribute()
	{
		if ( ! array_key_exists('admin', $this->additional))
		{
			$admin_id = $this->getAttribute('admin_id');

			$this->additional['admin'] = AdminModel::find($admin_id);
		}

		return $this->additional['admin'];
	}
}