<?php namespace App\Deposit\Handler\Form\DepositPayment;

use App\Deposit\DepositFactory;
use App\Purse\Model\PurseModel;
use Core\App\App;
use Core\FormHandler\FormHandlerInterface;
use App\Deposit\Job\CreateDepositPaymentInvoice;
use App\User\UserFactory;
use TF\Support\Collection;

class DepositPaymentFormHandler implements FormHandlerInterface
{
	/**
	 * Doi tuong request
	 *
	 * @var DepositPaymentFormRequest
	 */
	protected $request;

	/**
	 * Doi tuong validator
	 *
	 * @var DepositPaymentFormValidator
	 */
	protected $validator;


	/**
	 * Khoi tao doi tuong
	 *
	 * @param DepositPaymentFormRequest $request
	 */
	public function __construct(DepositPaymentFormRequest $request)
	{
		$this->request = $request;

		$this->validator = new DepositPaymentFormValidator($request);
	}

	/**
	 * Lay params
	 *
	 * @return array
	 */
	protected function params()
	{
		return ['purse_number', 'amount'];
	}

	/**
	 * Gan rules validate
	 *
	 * @return array
	 */
	public function validation()
	{
		$rules = $this->validator->rules();

		App::validation()->setRules($rules);

		return array_keys($rules);
	}

	/**
	 * Xu ly form khi du lieu hop le
	 *
	 * @return mixed
	 */
	public function submit()
	{
		if ( ! $this->validator->validate())
		{
			return array_merge(
				$this->validator->error(),
				['complete' => false]
			);
		}

		return $this->request->isPageConfirm()
			? $this->submitConfirm()
			: $this->submitForm();
	}

	/**
	 * Submit page confirm
	 *
	 * @return string
	 */
	protected function submitConfirm()
	{
		DepositPaymentForm::delete();

		$purse = $this->request->getPurse();

		$amount = $this->request->get('amount');

		$deposit = (new CreateDepositPaymentInvoice($purse, $amount))->handle();

		return $deposit->invoice->url('payment');
	}

	/**
	 * Submit page form
	 *
	 * @return string
	 */
	protected function submitForm()
	{
		lib('captcha')->reset();

		$data = $this->request->only($this->params());

		$form = new DepositPaymentForm($data);

		$form->save();

		return site_url('deposit/confirm');
	}

	/**
	 * Lay form view data
	 *
	 * @return array
	 */
	public function form()
	{
		return $this->request->isPageConfirm()
			? $this->formConfirm()
			: $this->formForm();
	}

	/**
	 * Xu ly form confirm
	 *
	 * @return array
	 */
	protected function formConfirm()
	{
		$form = DepositPaymentForm::get();

		if ( ! $form->purse)
		{
			return redirect('deposit');
		}

		return compact('form');
	}

	/**
	 * Xu ly form nhap du lieu
	 *
	 * @return array
	 */
	protected function formForm()
	{
		$user = UserFactory::auth()->user();

		$purses = $user->purses;

		$purses_setting = $this->makePursesSetting($purses);

		return compact('user', 'purses', 'purses_setting');
	}

	/**
	 * Tao setting cho list purses
	 *
	 * @param Collection $purses
	 * @return array
	 */
	protected function makePursesSetting(Collection $purses)
	{
		$result = [];

		foreach ($purses as $purse)
		{
			$result[$purse->id] = $this->makePurseSetting($purse);
		}

		return $result;
	}

	/**
	 * Tao purse setting
	 *
	 * @param PurseModel $purse
	 * @return array
	 */
	protected function makePurseSetting(PurseModel $purse)
	{
		$currency = $purse->currency;

		$setting = DepositFactory::deposit()->settingForCurrency($currency);

		$setting = array_only($setting, ['amount_min', 'amount_max']);

		foreach ($setting as $key => $value)
		{
			$setting['format_'.$key] = currency_format_amount($value, $currency->id);
		}

		return $setting;
	}

	/**
	 * Xu ly form error
	 *
	 * @return array
	 */
	public function error()
	{
		return [];
	}

}