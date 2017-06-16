<?php namespace App\Withdraw\Command;

use Core\Support\OptionsAccess;
use App\Admin\Model\AdminModel as AdminModel;
use App\Purse\Model\PurseModel as PurseModel;

class WithdrawAdminCommand extends OptionsAccess
{
	protected $config = [

		/**
		 * Admin thuc hien rut
		 *
		 * @var AdminModel
		 */
		'admin' => [
			'required' => true,
		],

		/**
		 * Purse can rut
		 *
		 * @var PurseModel
		 */
		'purse' => [
			'required' => true,
		],

		/**
		 * So tien can rut (tinh theo tien te cua vi)
		 *
		 * @var float
		 */
		'amount' => [
			'required' => true,
			'cast' => 'float',
		],

		/**
		 * Ghi chu
		 *
		 * @var string
		 */
		'desc' => [
			'required' => true,
			'cast' => 'string',
		],

		/**
		 * Thong tin deposit
		 *
		 * @var array
		 */
		'data' => [
			'default' => [],
			'allowed_types' => 'array',
		],

	];

	/**
	 * Lay admin
	 *
	 * @return AdminModel
	 */
	public function getAdmin()
	{
		return $this->get('admin');
	}

	/**
	 * Lay purse
	 *
	 * @return PurseModel
	 */
	public function getPurse()
	{
		return $this->get('purse');
	}

}