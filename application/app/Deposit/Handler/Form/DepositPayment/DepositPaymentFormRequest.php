<?php namespace App\Deposit\Handler\Form\DepositPayment;
use Core\Support\RequestAccess;
use Core\Support\Number;
use App\Purse\PurseFactory as PurseFactory;
use App\Purse\Model\PurseModel as PurseModel;
use App\User\UserFactory as UserFactory;
use App\User\Model\UserModel as UserModel;

class DepositPaymentFormRequest extends RequestAccess
{
	/**
	 * Doi tuong UserModel
	 *
	 * @var UserModel
	 */

	protected $user;


	/**
	 * Khoi tao doi tuong
	 *
	 * @param array $input
	 */
	public function __construct(array $input)
	{
		parent::__construct($input);

		$this->user = UserFactory::auth()->user();

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

			$this->data['purse'] = PurseFactory::purse()->findByNumber($purse_number);
		}

		return $this->data['purse'];
	}

	/**
	 * Lay thong tin user
	 *
	 * @return UserModel
	 */
	public function getUser()
	{
		return $this->user;
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