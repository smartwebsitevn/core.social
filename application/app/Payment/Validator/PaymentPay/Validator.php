<?php namespace App\Payment\Validator\PaymentPay;

use App\Payment\PaymentFactory as PaymentFactory;
use App\Transaction\Model\TranModel as TranModel;
use App\Payment\Model\PaymentModel as PaymentModel;
use App\Transaction\TranFactory;
use App\User\Model\UserModel as UserModel;
use App\Invoice\Model\InvoiceModel as InvoiceModel;

class Validator
{
	/**
	 * Thong tin tran
	 *
	 * @var TranModel
	 */
	protected $tran;

	/**
	 * Thong tin payment
	 *
	 * @var PaymentModel
	 */
	protected $payment;

	/**
	 * Thong tin nguoi thanh toan
	 *
	 * @var UserModel
	 */
	protected $user;


	/**
	 * Khoi tao doi tuong
	 *
	 * @param TranModel    $tran
	 * @param PaymentModel $payment
	 * @param UserModel    $user
	 */
	public function __construct(TranModel $tran, PaymentModel $payment, UserModel $user)
	{
		$this->tran = $tran;
		$this->payment = $payment;
		$this->user = $user;
	}

	/**
	 * Thuc hien validate
	 *
	 * @throws PaymentPayException
	 */
	public function validate()
	{
		if ( ! $this->canPay())
		{
		    $this->throwException(Error::CAN_NOT_PAY_TRAN);
		}

		if ( ! $this->checkPayment())
		{
			$this->throwException(Error::PAYMENT_INVALID);
		}

		if ( ! $this->checkTranOwner())
		{
			$this->throwException(Error::TRAN_OWNER_INVALID);
		}

		if ( ! $this->userCanUsePayment())
		{
		    $this->throwException(Error::USER_CAN_NOT_USE_PAYMENT);
		}

		if ( ! $this->canUsePaymentForInvoice())
		{
			$this->throwException(Error::CAN_NOT_USE_PAYMENT_FOR_INVOICE);
		}

		if ( ! $this->checkPaymentAmountDailyOfUser())
		{
		    $this->throwException(Error::PAYMENT_AMOUNT_DAILY_EXCEEDED);
		}
	}

	/**
	 * Kiem tra tran co the pay hay khong
	 *
	 * @return bool
	 */
	protected function canPay()
	{
		return $this->tran->can('pay') && $this->getInvoice()->can('pay');
	}

	/**
	 * Kiem tra payment
	 *
	 * @return bool
	 */
	protected function checkPayment()
	{
		return $this->payment->status && $this->payment->can('payment');
	}

	/**
	 * Kiem tra nguoi tao va nguoi thanh toan
	 *
	 * @return bool
	 */
	protected function checkTranOwner()
	{
		return (int) $this->tran->user_id == (int) $this->user->id;
	}

	/**
	 * Kiem tra user co duoc phep su dung payment hay khong
	 *
	 * @return bool
	 */
	protected function userCanUsePayment()
	{
		return PaymentFactory::payment()->canUseByUser($this->payment, $this->user);
	}

	/**
	 * Kiem tra co the su dung payment cho invoice hay khong
	 *
	 * @return bool
	 */
	protected function canUsePaymentForInvoice()
	{
		return PaymentFactory::payment()->canUseForInvoice($this->payment, $this->getInvoice());
	}

	/**
	 * Kiem tra tong so tien thanh toan trong ngay cua user
	 *
	 * @return bool
	 */
	protected function checkPaymentAmountDailyOfUser()
	{
		$payment = $this->payment;

		$user = $this->user;

		$total = TranFactory::tran()->totalPaidAmountByPaymentOfUserInToday($payment, $user);

		$amount = $this->tran->amount;

		$max = array_get($user->user_group->payments, $payment->id.'.amount_daily');

		return ($max && $total + $amount > $max) ? false : true;
	}

	/**
	 * Lay thong tin invoice
	 *
	 * @return InvoiceModel
	 */
	public function getInvoice()
	{
		return $this->tran->invoice;
	}

	/**
	 * Throw exception
	 *
	 * @param string $error
	 * @param array  $replace
	 * @throws PaymentPayException
	 */
	public function throwException($error, $replace = [])
	{
		$message = PaymentFactory::service()->errorLang($error, $replace);

		throw new PaymentPayException($error, $message);
	}

}