<?php namespace App\Transfer\Handler\Form\Transfer;

use App\Purse\Model\PurseModel;
use App\Purse\PurseFactory;
use Core\Support\RequestAccess;
use App\User\UserFactory;
use App\User\Model\UserModel;
use Core\Support\Number;

class TransferFormRequest extends RequestAccess
{
	/**
	 * Doi tuong UserModel
	 *
	 * @var UserModel
	 */
	protected $sender;


	/**
	 * Khoi tao doi tuong
	 *
	 * @param array $input
	 */
	public function __construct(array $input)
	{
		parent::__construct($input);

		$this->sender = UserFactory::auth()->user();

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

		$this->input['desc'] = strip_tags($this->get('desc'));
	}

	/**
	 * Lay thong tin nguoi gui
	 *
	 * @return UserModel
	 */
	public function getSender()
	{
		return $this->sender;
	}

	/**
	 * Lay thong tin purse gui
	 *
	 * @return PurseModel|null
	 */
	public function getSenderPurse()
	{
		if ( ! array_key_exists('sender_purse', $this->data))
		{
			$sender_purse_number = $this->get('sender_purse_number');

			$this->data['sender_purse'] = PurseFactory::purse()->findByNumber($sender_purse_number);
		}

		return $this->data['sender_purse'];
	}

	/**
	 * Lay thong tin purse nhan
	 *
	 * @return PurseModel|null
	 */
	public function getReceiverPurse()
	{
		if ( ! array_key_exists('receiver_purse', $this->data))
		{
			$receiver_purse_number = $this->get('receiver_purse_number');

			$this->data['receiver_purse'] = $this->findReceiverPurse($receiver_purse_number);
		}

		return $this->data['receiver_purse'];
	}

	/**
	 * Tim purse nhan
	 *
	 * @param string $input
	 * @return PurseModel|null
	 */
	protected function findReceiverPurse($input)
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