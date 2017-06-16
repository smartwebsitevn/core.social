<?php namespace App\DepositCard\Handler\Form\DepositCard;

use App\Purse\Model\PurseModel;
use App\DepositCard\DepositCardFactory;
use Core\App\App;
use Core\FormHandler\FormHandlerInterface;
use App\DepositCard\Command\DepositCardCommand;
use App\DepositCard\Job\DepositCard as DepositCardJob;
use App\DepositCard\Handler\Form\DepositCard\DepositCardFormRequest;
use App\DepositCard\Handler\Form\DepositCard\DepositCardFormValidator;
use App\User\UserFactory as UserFactory;
use TF\Support\Collection;

class DepositCardFormHandler implements FormHandlerInterface
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
	public function __construct(DepositCardFormRequest $request)
	{
		$this->request = $request;

		$this->validator = new DepositCardFormValidator($request);
	}

	/**
	 * Lay params
	 *
	 * @return array
	 */
	protected function params()
	{
		return ['type', 'code', 'serial'];
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

		DepositCardForm::delete();

		$command = new DepositCardCommand([
			'sender'         => $this->request->getSender(),
			'sender_purse'   => $this->request->getSenderPurse(),
			'receiver_purse' => $this->request->getReceiverPurse(),
			'amount'         => $this->request->get('amount'),
			'desc'           => $this->request->get('desc'),
		]);

		$transfer = (new DepositCardJob($command))->handle();

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

		$form = new DepositCardForm($data);

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
		if ( ! $form = DepositCardForm::get())
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
	 * Xu ly form error
	 *
	 * @return array
	 */
	public function error()
	{
		return [];
	}

}