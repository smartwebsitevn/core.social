<?php namespace App\Withdraw\Handler\Form\WithdrawAdmin;

use Core\Support\RequestAccess;
use Core\Support\Number;
use App\Purse\PurseFactory as PurseFactory;
use App\Purse\Model\PurseModel as PurseModel;

class WithdrawAdminFormRequest extends RequestAccess
{
	/**
	 * Khoi tao doi tuong
	 *
	 * @param array $input
	 */
	public function __construct(array $input)
	{
		parent::__construct($input);

		$this->handleInput();
	}

	/**
	 * Xu ly input
	 */
	protected function handleInput()
	{
		foreach ($this->input as $key => &$value)
		{
			if (in_array($key, ['amount']))
			{
				$value = Number::handleAmountInput($value);
			}
		}
	}

	/**
	 * Lay thong tin purse
	 *
	 * @return PurseModel|null
	 */
	public function getPurse()
	{
		if ( ! array_key_exists('purse', $this->data))
		{
			$purse_number = $this->get('purse_number');

			$this->data['purse'] = $this->findPurse($purse_number);
		}

		return $this->data['purse'];
	}

	/**
	 * Tim purse
	 *
	 * @param string $input
	 * @return PurseModel|null
	 */
	protected function findPurse($input)
	{
		$purse = PurseFactory::purse()->findByNumber($input);

		return $purse ?: PurseFactory::purse()->findByUserKey($input);
	}

	/**
	 * Kiem tra page hien tai co phai confirm hay khong
	 *
	 * @return bool
	 */
	public function isPageConfirm()
	{
		return $this->get('page') == 'confirm';
	}

}