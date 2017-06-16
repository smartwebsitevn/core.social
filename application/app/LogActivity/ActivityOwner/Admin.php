<?php namespace App\LogActivity\ActivityOwner;

use App\LogActivity\Library\ActivityOwner;
use App\Admin\AdminFactory;
use App\Admin\Model\AdminModel;

class Admin extends ActivityOwner
{
	/**
	 * Doi tuong AdminModel
	 *
	 * @var AdminModel
	 */
	protected $admin;

	/**
	 * Khoi tao doi tuong
	 *
	 * @param AdminModel $admin
	 */
	public function __construct(AdminModel $admin = null)
	{
		if (is_null($admin))
		{
			$admin = AdminFactory::auth()->user();
		}

		$this->admin = $admin;

		parent::__construct('admin', $admin->getKey(), $admin->toArray());
	}
}