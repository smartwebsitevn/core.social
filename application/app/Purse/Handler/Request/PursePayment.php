<?php namespace App\Purse\Handler\Request;

use Core\App\App;
use App\Purse\Model\PurseModel as PurseModel;
use App\Purse\Validator\PursePayment\Validator as PursePaymentValidator;
use App\Purse\Validator\PursePayment\PursePaymentException;
use App\Transaction\Model\TranModel as TranModel;
use App\User\UserFactory as UserFactory;
use App\Accountant\AccountantFactory as AccountantFactory;
use App\Accountant\ChangeBalanceReason\Payment as PaymentReason;

class PursePayment
{
	/**
	 * So tien can thanh toan
	 *
	 * @var float
	 */
	protected $amount;

	/**
	 * Thong tin tran
	 *
	 * @var TranModel
	 */
	protected $tran;

	/**
	 * Input
	 *
	 * @var array
	 */
	protected $input = [];

	/**
	 * Data
	 *
	 * @var array
	 */
	protected $data = [];

	/**
	 * Errors
	 *
	 * @var array
	 */
	protected $errors = [];


	/**
	 * PursePayment constructor.
	 *
	 * @param float     $amount
	 * @param TranModel $tran
	 */
	public function __construct($amount, TranModel $tran)
	{
		$this->amount = $amount;
		$this->tran = $tran;
		$this->input = t('input')->post();
	}

	/**
	 * Thuc hien validate
	 *
	 * @return bool
	 */
	public function validate()
	{
		if ( ! $this->validateForm()) return false;

		if ( ! $this->validatePursePayment()) return false;

		return true;
	}

	/**
	 * Validate form
	 *
	 * @return bool
	 */
	protected function validateForm()
	{
		$rules = $this->getRulesValidateForm();

		App::validation()->setRules($rules);

		if ( ! App::validation()->run())
		{
			$this->errors = App::validation()->errors(array_keys($rules));

			return false;
		}

		if ( ! $this->checkUserSecurity())
		{
			$this->errors[mod('user_security')->param()] = mod('user_security')->getErrorMessage();

			return false;
		}

		if ( ! $this->getPurse())
		{
			$this->errors['purse_id'] = lang('notice_value_not_exist', lang('purse'));

			return false;
		}

		return true;
	}

	/**
	 * Lay validate form rules
	 *
	 * @return array
	 */
	protected function getRulesValidateForm()
	{
		$user_security_param = mod('user_security')->param();

		return [

			'purse_id' => [
				'label' => lang('purse'),
				'rules' => 'required',
			],

			$user_security_param => [
				'label' => lang('security_value'),
				'rules' => 'required',
			],

			'security_code' => 'required|captcha[four]',

		];
	}

	/**
	 * Kiem tra user_security
	 *
	 * @return bool
	 */
	protected function checkUserSecurity()
	{
		$param = mod('user_security')->param();

		$value = $this->input($param);

		return mod('user_security')->valid('payment', $value);
	}

	/**
	 * Validate thanh toan bang purse
	 *
	 * @return bool
	 */
	protected function validatePursePayment()
	{
		$user = UserFactory::auth()->user();

		$validator = new PursePaymentValidator(
			$user, $this->getPurse(), $this->amount, $this->tran
		);

		try
		{
			$validator->validate();

			return true;
		}
		catch (PursePaymentException $e)
		{
			$this->errors['payment'] = $e->getMessage();

			return false;
		}
	}

	/**
	 * Xu ly khi thanh toan thanh cong
	 */
	public function success()
	{
		lib('captcha')->del('four');

		$this->subPurseBalance();
	}

	/**
	 * Tru so du cua vi
	 */
	protected function subPurseBalance()
	{
		$reason = PaymentReason::make($this->tran);

		AccountantFactory::balance()->sub($this->getPurse(), $this->amount, $reason);
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
			$this->data['purse'] = PurseModel::find($this->input('purse_id'));
		}

		return $this->data['purse'];
	}

	/**
	 * Lay input
	 *
	 * @param string $key
	 * @param mixed  $default
	 * @return mixed
	 */
	public function input($key = null, $default = null)
	{
		return array_get($this->input, $key, $default);
	}

	/**
	 * Lay errors
	 *
	 * @return array
	 */
	public function errors()
	{
		return $this->errors;
	}
}