<?php namespace App\Transfer\Handler\Form\Transfer;

use App\Purse\Model\PurseModel;
use App\Transfer\TransferFactory;
use Core\App\App;
use Core\FormHandler\FormHandlerInterface;
use App\Transfer\Command\TransferCommand;
use App\Transfer\Job\Transfer as TransferJob;
use App\Transfer\Handler\Form\Transfer\TransferFormRequest;
use App\Transfer\Handler\Form\Transfer\TransferFormValidator;
use App\User\UserFactory as UserFactory;
use TF\Support\Collection;

class TransferFormHandler implements FormHandlerInterface
{
	/**
	 * Doi tuong TransferFormRequest
	 *
	 * @var TransferFormRequest
	 */
	protected $request;

	/**
	 * Doi tuong TransferFormValidator
	 *
	 * @var TransferFormValidator
	 */
	protected $validator;


	/**
	 * Khoi tao doi tuong
	 *
	 * @param TransferFormRequest $request
	 */
	public function __construct(TransferFormRequest $request)
	{
		$this->request = $request;

		$this->validator = new TransferFormValidator($request);
	}

	/**
	 * Lay params
	 *
	 * @return array
	 */
	protected function params()
	{
		return ['sender_purse_number', 'receiver_purse_number', 'amount', 'desc'];
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
		lib('csrf')->delay();

		TransferForm::delete();

		$command = new TransferCommand([
			'sender'         => $this->request->getSender(),
			'sender_purse'   => $this->request->getSenderPurse(),
			'receiver_purse' => $this->request->getReceiverPurse(),
			'amount'         => $this->request->get('amount'),
			'desc'           => $this->request->get('desc'),
		]);

		$transfer = (new TransferJob($command))->handle();

		set_message(lang('notice_transfer_success'));

		return $transfer->send_invoice_order->url('view');
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

		$form = new TransferForm($data);

		$form->save();

		mod('user_security')->send('transfer');

		return site_url('transfer/confirm');
	}

	/**
	 * Lay form data
	 *
	 * @return array
	 */
	protected function data()
	{
		$data = $this->request->only($this->params());

		$data['receiver_purse_number'] = $this->request->getReceiverPurse()->number;

		return $data;
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
		if ( ! $form = TransferForm::get())
		{
			return redirect('transfer');
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
		$user = $this->request->getSender();

		$purses = $user->purses;

		$purses_setting = $this->makePursesSetting($purses);

		$url_load_name = site_url('transfer/api_user_name');

		return compact('user', 'purses', 'purses_setting', 'url_load_name');
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

		$setting = TransferFactory::transfer()->settingForCurrency($currency);

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