<?php namespace App\Withdraw\Handler\Form\WithdrawPayment;

use Core\Support\RequestAccess;
use Core\Support\Number;
use App\Purse\PurseFactory as PurseFactory;
use App\Purse\Model\PurseModel as PurseModel;
use App\Payment\PaymentFactory as PaymentFactory;
use App\Payment\Model\PaymentModel as PaymentModel;
use App\User\UserFactory as UserFactory;
use App\User\Model\UserModel as UserModel;

class WithdrawPaymentFormRequest extends RequestAccess
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
	 * Lay thong tin user
	 *
	 * @return UserModel
	 */
	public function getUser()
	{
		return $this->user;
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
	 * Lay thong tin nhan
	 *
	 * @return array
	 */
	function getReceiver()
	{
	    $this->data['receiver'] = $this->get('receiver');
	    return $this->data['receiver'];
	}
	
	/**
	 * Lay thong tin payment
	 *
	 * @return PaymentModel|null
	 */
	public function getPayment()
	{
		if ( ! array_key_exists('payment', $this->data))
		{
			$payment_id = $this->get('payment_id');

			$this->data['payment'] = PaymentFactory::paymentManager()->findById($payment_id);
		}

		return $this->data['payment'];
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