<?php namespace App\Withdraw\Handler\Form\WithdrawPayment;

use App\Purse\Model\PurseModel;
use App\Withdraw\WithdrawFactory;
use TF\Support\Collection;
use Core\App\App;
use Core\FormHandler\FormHandlerInterface;
use App\Payment\PaymentFactory as PaymentFactory;
use App\Payment\Model\PaymentModel as PaymentModel;
use App\Withdraw\Command\WithdrawPaymentCommand;
use App\Withdraw\Job\WithdrawPayment as WithdrawPaymentJob;

class WithdrawPaymentFormHandler implements FormHandlerInterface
{
	/**
	 * Doi tuong request
	 *
	 * @var WithdrawPaymentFormRequest
	 */
	protected $request;

	/**
	 * Doi tuong validator
	 *
	 * @var WithdrawPaymentFormValidator
	 */
	protected $validator;


	/**
	 * Khoi tao doi tuong
	 *
	 * @param WithdrawPaymentFormRequest $request
	 */
	public function __construct(WithdrawPaymentFormRequest $request)
	{
		$this->request = $request;

		$this->validator = new WithdrawPaymentFormValidator($request);
	}

	/**
	 * Lay params
	 *
	 * @return array
	 */
	protected function params()
	{
		return ['purse_number', 'amount', 'payment_id', 'receiver'];
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
		WithdrawPaymentForm::delete();

		$payment = $this->request->getPayment();

		$receiver = $this->getReceiverValue();
		$receiver = $payment->paygateServiceInstance()->withdraw()->value($receiver);

		$command = new WithdrawPaymentCommand([
			'purse'    => $this->request->getPurse(),
			'amount'   => $this->request->get('amount'),
			'payment'  => $payment,
			'receiver' => $receiver,
		]);

		$withdraw = (new WithdrawPaymentJob($command))->handle();

		set_message(lang('notice_update_success'));

		return $withdraw->invoice_order->url('view');
	}

	/**
	 * Submit page form
	 *
	 * @return string
	 */
	protected function submitForm()
	{
		lib('captcha')->reset();

		$data = $this->data();

		$form = new WithdrawPaymentForm($data);

		$form->save();

		mod('user_security')->send('withdraw');

		return site_url('withdraw/confirm');
	}

	/**
	 * Lay form data
	 *
	 * @return array
	 */
	protected function data()
	{
		$data = $this->request->only($this->params());

		$data['receiver'] = $this->getReceiverValue();

		return $data;
	}

	/**
	 * Lay gia tri cua receiver
	 *
	 * @return array
	 */
	protected function getReceiverValue()
	{
		$payment = $this->request->getPayment();

		return $this->request->get('receiver.'.$payment->id);
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
		if ( ! $form = WithdrawPaymentForm::get())
		{
			return redirect('withdraw');
		}

		$receiver = $this->formConfirmWithdrawReceiver($form->payment, $form->receiver);

		return compact('form', 'receiver');
	}

	/**
	 * Tao du lieu view withdraw receiver
	 *
	 * @param PaymentModel $payment
	 * @param array        $receiver
	 * @return array|string
	 */
	protected function formConfirmWithdrawReceiver(PaymentModel $payment, array $receiver)
	{
		return $payment->paygateServiceInstance()->withdraw()->formConfirm($receiver);
	}

	/**
	 * Xu ly form nhap du lieu
	 *
	 * @return array
	 */
	protected function formForm()
	{
		$user = $this->request->getUser();

		$purses = $user->purses;

		$payments = $this->getPayments();

		$payments_form = $this->makePaymentsForm($payments);

		$purses_setting = $this->makePursesSetting($purses);

		return compact('user', 'purses', 'payments', 'payments_form', 'purses_setting');
	}

	/**
	 * Lay danh sach payments
	 *
	 * @return Collection
	 */
	protected function getPayments()
	{
		$list = PaymentFactory::paymentManager()->listActive();

		return $list->filter(function(PaymentModel $payment)
		{
			$user = $this->request->getUser();

			return (
				$payment->can('withdraw')
				&& PaymentFactory::payment()->canUseByUser($payment, $user)
			);
		});
	}

	/**
	 * Tao payments form
	 *
	 * @param Collection $payments
	 * @return Collection
	 */
	protected function makePaymentsForm(Collection $payments)
	{
		return $payments->map(function(PaymentModel $payment)
		{
			$withdraw = $payment->paygateServiceInstance()->withdraw();

			$config = $withdraw->formConfig("receiver[{$payment->id}]");

			return [
				'payment_id' => $payment->id,
				'config'     => $config,
				'form'       => $withdraw->form($config),
			];
		});
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

		$setting = WithdrawFactory::withdraw()->settingForCurrency($currency);

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