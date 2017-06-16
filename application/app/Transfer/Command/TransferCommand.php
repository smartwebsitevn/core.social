<?php namespace App\Transfer\Command;

use App\Currency\Model\CurrencyModel;
use App\Transfer\TransferFactory;
use Core\Support\OptionsAccess;
use App\User\Model\UserModel;
use App\Purse\Model\PurseModel;

class TransferCommand extends OptionsAccess
{
	protected $config = [

		/**
		 * Nguoi gui
		 *
		 * @var UserModel
		 */
		'sender' => [
			'required' => true,
		],

		/**
		 * Purse gui
		 *
		 * @var PurseModel
		 */
		'sender_purse' => [
			'required' => true,
		],

		/**
		 * Purse nhan
		 *
		 * @var PurseModel
		 */
		'receiver_purse' => [
			'required' => true,
		],

		/**
		 * So tien chuyen (tinh theo tien te cua purse gui)
		 *
		 * @var float
		 */
		'amount' => [
			'required' => true,
			'cast' => 'float',
		],

		/**
		 * Noi dung giao dich
		 *
		 * @var string
		 */
		'desc' => [
			'cast' => 'string',
		],

	];

	/**
	 * Khoi tao doi tuong
	 *
	 * @param array $options
	 */
	public function __construct(array $options)
	{
		parent::__construct($options);

		$this->makeOptionsAmounts();
	}

	/**
	 * Tao cac options amounts
	 */
	protected function makeOptionsAmounts()
	{
		$amounts = TransferFactory::transfer()->getAmounts(
			$this->getSenderPurse(), $this->get('amount')
		);

		$this->options = array_merge($this->options, $amounts);
	}

	/**
	 * Lay thong tin nguoi gui
	 *
	 * @return UserModel
	 */
	public function getSender()
	{
		return $this->get('sender');
	}

	/**
	 * Lay thong tin purse gui
	 *
	 * @return PurseModel
	 */
	public function getSenderPurse()
	{
		return $this->get('sender_purse');
	}

	/**
	 * Lay currency gui
	 *
	 * @return CurrencyModel
	 */
	public function getSendCurrency()
	{
		return $this->getSenderPurse()->currency;
	}

	/**
	 * Lay thong tin purse nhan
	 *
	 * @return PurseModel
	 */
	public function getReceiverPurse()
	{
		return $this->get('receiver_purse');
	}

	/**
	 * Lay currency nhan
	 *
	 * @return mixed
	 */
	public function getReceiveCurrency()
	{
		return $this->getReceiverPurse()->currency;
	}

}