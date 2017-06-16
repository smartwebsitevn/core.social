<?php namespace App\Withdraw\Handler\Form\WithdrawPayment;

use App\Withdraw\WithdrawFactory as WithdrawFactory;
use App\Payment\PaymentFactory as PaymentFactory;
use App\Purse\PurseFactory as PurseFactory;
use Core\Support\Number;

class WithdrawPaymentFormValidator
{
	/**
	 * Doi tuong Request
	 *
	 * @var WithdrawPaymentFormRequest
	 */
	protected $request;

	/**
	 * Form errors
	 *
	 * @var array
	 */
	protected $errors = [];


	/**
	 * Khoi tao doi tuong
	 *
	 * @param WithdrawPaymentFormRequest $request
	 */
	public function __construct(WithdrawPaymentFormRequest $request)
	{
		$this->request = $request;
	}

	/**
	 * Lay rules
	 *
	 * @return array
	 */
	public function rules()
	{
		$rules = [

			'purse_number' => 'required',

			'amount' => 'required',

			'payment_id' => [
				'label' => lang('payment'),
				'rules' => 'required',
			],

		];

		$rules = array_merge($rules, $this->makeReceiverRules());

		if ($this->request->isPageConfirm())
		{
			$rules[mod('user_security')->param()] = mod('user_security')->rules();
		}
		else
		{
			$rules['security_code'] = 'required|captcha';
		}

		return $rules;
	}

	/**
	 * Tao receiver rules
	 *
	 * @return array
	 */
	protected function makeReceiverRules()
	{
		if ( ! $this->checkPayment()) return [];

		$payment = $this->request->getPayment();

		$withdraw = $payment->paygateServiceInstance()->withdraw();

		return $withdraw->rules("receiver[{$payment->id}]");
	}

	/**
	 * Thuc hien validate
	 *
	 * @return bool
	 */
	public function validate()
	{
		if ( ! $this->checkPurse())
		{
			$this->errors['purse_number'] = lang('notice_value_invalid', lang('purse'));

			return false;
		}

		if ( ! $this->checkAmount())
		{
			$this->errors['amount'] = lang('notice_value_invalid', lang('amount'));

			return false;
		}

		if ( ! $this->checkPayment())
		{
			$this->errors['payment_id'] = lang('notice_value_invalid', lang('payment'));

			return false;
		}

		$error = null;
		if ( ! $this->checkReceiver($error))
		{
			$this->errors['receiver'] = $error ?: lang('notice_value_invalid', lang('receiver'));

			return false;
		}

		if ( ! $this->checkReceiveAmount())
		{
			$this->errors['amount'] = lang('notice_value_invalid', lang('amount'));

			return false;
		}

		if ($this->request->isPageConfirm())
		{
			if ( ! $this->checkUserSecurity())
			{
				$this->errors[mod('user_security')->param()] = mod('user_security')->getErrorMessage();

				return false;
			}
		}

		return true;
	}

	/**
	 * Kiem tra purse
	 *
	 * @return bool
	 */
	protected function checkPurse()
	{
		$purse = $this->request->getPurse();

		$user = $this->request->getUser();

		return ($purse && PurseFactory::purse()->canUseByUser($purse, $user));
	}

	/**
	 * Kiem tra amount
	 *
	 * @return bool
	 */
	protected function checkAmount()
	{
		$amount = $this->request->get('amount');

		$purse = $this->request->getPurse();

		$setting = WithdrawFactory::withdraw()->settingForCurrency($purse->currency);

		return (
			$amount > 0
			&& $amount <= $purse->balance
			&& Number::validAmountLimit($amount, $setting['amount_min'], $setting['amount_max'] ?: null)
		);
	}

	/**
	 * Kiem tra payment
	 *
	 * @return bool
	 */
	protected function checkPayment()
	{
		$payment = $this->request->getPayment();

		$user = $this->request->getUser();

		return (
			$payment->can('withdraw')
			&& PaymentFactory::payment()->canUseByUser($payment, $user)
		);
	}

	/**
	 * Kiem tra receiver
	 *
	 * @param string $error
	 * @return bool
	 */
	protected function checkReceiver(&$error = null)
	{
		$payment = $this->request->getPayment();

		$input = $this->request->get('receiver.'.$payment->id, []);

		return $payment->paygateServiceInstance()->withdraw()->validate($input, $error);
	}

	/**
	 * Kiem tra so tien se nhan qua payment
	 *
	 * @return bool
	 */
	protected function checkReceiveAmount()
	{
		$purse = $this->request->getPurse();

		$amount = $this->request->get('amount');

		$payment = $this->request->getPayment();

		$receiver = $this->request->getReceiver();

		$amounts = WithdrawFactory::withdraw()->getAmounts($purse, $amount, $payment, $receiver);
		return $amounts['receive_amount'] > 0;
	}

	/**
	 * Kiem tra user_security
	 *
	 * @return bool
	 */
	protected function checkUserSecurity()
	{
		$param = mod('user_security')->param();

		$value = $this->request->get($param);

		return mod('user_security')->valid('withdraw', $value);
	}

	/**
	 * Lay error
	 *
	 * @param string $key
	 * @return string
	 */
	public function error($key = null)
	{
		return array_get($this->errors, $key);
	}

}