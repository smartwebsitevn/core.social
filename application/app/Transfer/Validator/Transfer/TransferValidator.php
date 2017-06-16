<?php namespace App\Transfer\Validator\Transfer;

use App\Accountant\AccountantFactory;
use App\Purse\PurseFactory;
use App\Transfer\TransferFactory;
use App\Transfer\Command\TransferCommand;
use App\Transfer\Validator\Transfer\TransferError as Error;
use Core\Support\Number;

class TransferValidator
{
	/**
	 * Doi tuong TransferCommand
	 *
	 * @var TransferCommand
	 */
	protected $command;


	public static function _t()
	{
		$command = new TransferCommand([
			'sender' => \App\User\Model\UserModel::find(1),
			'sender_purse' => \App\Purse\Model\PurseModel::find(2),
			'receiver_purse' => \App\Purse\Model\PurseModel::find(5),
			'amount' => 100,
		]);

		$me = new static($command);

		$v = $me->validate();

		pr($v, 0);
	}

	/**
	 * Khoi tao doi tuong
	 *
	 * @param TransferCommand $command
	 */
	public function __construct(TransferCommand $command)
	{
		$this->command = $command;

		t('lang')->load('modules/transfer/common');
	}

	/**
	 * Thuc hien validate
	 *
	 * @throws TransferException
	 */
	public function validate()
	{
		if ( ! $this->checkSenderPurse())
		{
		    $this->throwException(Error::SENDER_PURSE_INVALID);
		}

		if ( ! $this->checkReceiverPurse())
		{
		    $this->throwException(Error::RECEIVER_PURSE_INVALID);
		}

		if ( ! $this->checkAmount())
		{
		    $this->throwException(Error::AMOUNT_INVALID);
		}

		if ( ! $this->checkSenderPurseBalance())
		{
		    $this->throwException(Error::SENDER_PURSE_BALANCE_NOT_ENOUGH);
		}

		if ( ! $this->checkSenderAmountDaily())
		{
		    $this->throwException(Error::SEND_AMOUNT_DAILY_EXCEEDED);
		}

		if ( ! $this->checkSenderPurseLastBalance())
		{
		    $this->throwException(Error::SENDER_PURSE_BALANCE_INVALID);
		}
	}

	/**
	 * Kiem tra sender_purse
	 *
	 * @return bool
	 */
	protected function checkSenderPurse()
	{
		return PurseFactory::purse()->canUseByUser(
			$this->command->getSenderPurse(),
			$this->command->getSender()
		);
	}

	/**
	 * Kiem tra receiver_purse
	 *
	 * @return bool
	 */
	protected function checkReceiverPurse()
	{
		$sender_purse = $this->command->getSenderPurse();

		$receiver_purse = $this->command->getReceiverPurse();

		return (
			$sender_purse->id != $receiver_purse->id // 2 purse phai khac nhau
			&& $sender_purse->currency_id == $receiver_purse->currency_id // 2 purse phai cung currency
		);
	}

	/**
	 * Kiem tra amount
	 *
	 * @return bool
	 */
	protected function checkAmount()
	{
		$amount = $this->command->amount;

		$currency = $this->command->getSendCurrency();

		$setting = TransferFactory::transfer()->settingForCurrency($currency);

		return (
			$amount > 0
			&& Number::validAmountLimit($amount, $setting['amount_min'], $setting['amount_max'] ?: null)
		);
	}

	/**
	 * Kiem tra so du cua purse gui
	 *
	 * @return bool
	 */
	protected function checkSenderPurseBalance()
	{
		$balance = $this->command->getSenderPurse()->balance;

		$net = $this->command->net;

		return $balance >= $net;
	}

	/**
	 * Kiem tra tong so tien da gui trong ngay cua nguoi gui
	 *
	 * @return bool
	 */
	protected function checkSenderAmountDaily()
	{
		$sender = $this->command->getSender();

		$currency = $this->command->getSendCurrency();

		$total = AccountantFactory::balance()->totalSubAmountOfUserInToday($sender);

		$send_amount = currency_convert_amount_default($this->command->net, $currency->id);

		$max = $sender->user_group->balance_send_amount_daily;

		return $max && ($total + $send_amount <= $max);
	}

	/**
	 * Kiem tra so du cuoi cua purse gui
	 *
	 * @return bool
	 */
	protected function checkSenderPurseLastBalance()
	{
		$sender_purse = $this->command->getSenderPurse();

		$last_balance = AccountantFactory::balance()->getLastBalanceOfPurse($sender_purse);

		return floor($sender_purse->balance) == floor($last_balance);
	}

	/**
	 * Throw exception
	 *
	 * @param string $error
	 * @param array  $replace
	 * @throws TransferException
	 */
	public function throwException($error, $replace = [])
	{
		$message = lang('error_'.$error, $replace);

		throw new TransferException($error, $message);
	}
}