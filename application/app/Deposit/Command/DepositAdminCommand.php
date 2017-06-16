<?php namespace App\Deposit\Command;

use Core\Support\OptionsAccess;
use App\Admin\Model\AdminModel as AdminModel;
use App\Purse\Model\PurseModel as PurseModel;

class DepositAdminCommand extends OptionsAccess
{
	protected $config = [

		/**
		 * Admin thuc hien nap
		 *
		 * @var AdminModel
		 */
		'admin' => [
			'required' => true,
		],

		/**
		 * Purse can nap
		 *
		 * @var PurseModel
		 */
		'purse' => [
			'required' => true,
		],

		/**
		 * So tien can nap (tinh theo tien te cua vi)
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