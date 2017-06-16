<?php namespace App\Admin\Service;

use App\Admin\Model\AdminModel;

class AdminAuth
{
	/**
	 * Khoi tao doi tuong
	 */
	public function __construct()
	{
		t('load')->helper('admin');
	}

	/**
	 * Lay thong tin admin hien tai
	 *
	 * @return AdminModel
	 */
	public function user()
	{
		$admin = admin_get_account_info();

		$admin = $admin ? (array) $admin : [];

		return AdminModel::newWithAttributes($admin);
	}

	/**
	 * Kiem tra admin hien tai da login hay chua
	 *
	 * @return bool
	 */
	public function logged()
	{
		return $this->user()->id ? true : false;
	}

	/**
	 * Kiem tra password
	 *
	 * @param string $password
	 * @return bool
	 */
	public function checkPasword($password)
	{
		$username = $this->user()->username;

		$password = security_encode($password, strtolower($username));

		return $this->user()->password === $password;
	}
}