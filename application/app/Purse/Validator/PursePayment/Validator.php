<?php namespace App\Purse\Validator\PursePayment;

use App\Purse\PurseFactory as PurseFactory;
use App\Purse\Model\PurseModel as PurseModel;
use App\User\Model\UserModel as UserModel;
use App\Transaction\Model\TranModel as TranModel;

class Validator
{
	/**
	 * User thanh toan
	 *
	 * @var UserModel
	 */
	protected $user;

	/**
	 * Purse thanh toan
	 *
	 * @var PurseModel
	 */
	protected $purse;

	/**
	 * So tien can thanh toan
	 *
	 * @var float
	 */
	protected $amount;

	/**
	 * Giao dich can thanh toan
	 *
	 * @var TranModel
	 */
	protected $tran;


	/**
	 * Validator constructor.
	 *
	 * @param UserModel  $user
	 * @param PurseModel $purse
	 * @param float      $amount
	 * @param TranModel  $tran
	 */
	public function __construct(UserModel $user, PurseModel $purse, $amount, TranModel $tran)
	{
		$this->user = $user;
		$this->purse = $purse;
		$this->amount = $amount;
		$this->tran = $tran;
	}

	/**
	 * Thuc hien validate
	 *
	 * @throws PursePaymentException
	 */
	public function validate()
	{
		if ($this->user->blocked)
		{
		    $this->throwException(Error::USER_BLOCKED);
		}

		if ($this->purse->user_id != $this->user->id)
		{
			$this->throwException(Error::PURSE_INVALID);
		}

		if ($this->purse->currency_id != $this->tran->currency_id)
		{
			$this->throwException(Error::PURSE_INVALID);
		}

		if ($this->purse->balance < $this->amount)
		{
			$this->throwException(Error::PURSE_BALANCE_NOT_ENOUGH);
		}
	}

	/**
	 * Throw exception
	 *
	 * @param string $error
	 * @param array  $replace
	 * @throws PursePaymentException
	 */
	public function throwException($error, $replace = [])
	{
		$message = PurseFactory::service()->errorLang($error, $replace);

		throw new PursePaymentException($error, $message);
	}
}