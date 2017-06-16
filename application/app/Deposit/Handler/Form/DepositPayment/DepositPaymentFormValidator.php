<?php namespace App\Deposit\Handler\Form\DepositPayment;

use App\Deposit\DepositFactory;
use App\Purse\PurseFactory as PurseFactory;
use Core\Support\Number;

class DepositPaymentFormValidator
{
	/**
	 * Doi tuong Request
	 *
	 * @var DepositPaymentFormRequest
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
	 * @param DepositPaymentFormRequest $request
	 */
	public function __construct(DepositPaymentFormRequest $request)
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
		];

		if ( ! $this->request->isPageConfirm())
		{
			$rules['security_code'] = 'required|captcha';
		}

		return $rules;
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

		$setting = DepositFactory::deposit()->settingForCurrency($purse->currency);

		return (
			$amount > 0
			&& Number::validAmountLimit($amount, $setting['amount_min'], $setting['amount_max'] ?: null)
		);
	}

	/**
	 * Lay doi tuong Request
	 *
	 * @return DepositPaymentFormRequest
	 */
	public function getRequest()
	{
		return $this->request;
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