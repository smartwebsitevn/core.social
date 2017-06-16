<?php namespace App\Deposit\Handler\Form\DepositAdmin;

use Core\FormHandler\FormHandlerInterface;
use Core\App\App;
use App\Deposit\Library\DepositAdminForm;
use App\Deposit\Command\DepositAdminCommand as DepositAdminCommand;
use App\Deposit\Job\DepositAdmin as DepositAdminJob;
use App\Admin\AdminFactory as AdminFactory;

class Factory implements FormHandlerInterface
{
	/**
	 * Doi tuong Request
	 *
	 * @var Request
	 */
	protected $request;

	/**
	 * Doi tuong Validator
	 *
	 * @var Validator
	 */
	protected $validator;


	/**
	 * Khoi tao doi tuong
	 *
	 * @param Request $request
	 */
	public function __construct(Request $request)
	{
		$this->request = $request;

		$this->validator = new Validator($request);
	}

	/**
	 * Lay params
	 *
	 * @return array
	 */
	protected function params()
	{
		return ['purse_number', 'amount', 'desc'];
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
		DepositAdminForm::delete();

		$command = new DepositAdminCommand([
			'admin'  => AdminFactory::auth()->user(),
			'purse'  => $this->request->getPurse(),
			'amount' => $this->request->get('amount'),
			'desc'   => $this->request->get('desc'),
		]);

		$deposit = (new DepositAdminJob($command))->handle();

		set_message(lang('notice_update_success'));

		return $deposit->invoice_order->adminUrl('view');
	}

	/**
	 * Submit page form
	 *
	 * @return string
	 */
	protected function submitForm()
	{
		$data = $this->data();

		$form = new DepositAdminForm($data);

		$form->save();

		return admin_url('deposit_admin/confirm');
	}

	/**
	 * Lay form data
	 *
	 * @return array
	 */
	protected function data()
	{
		$data = $this->request->only($this->params());

		$data['purse_number'] = $this->request->getPurse()->number;

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
			: [];
	}

	/**
	 * Xu ly form confirm
	 *
	 * @return array
	 */
	protected function formConfirm()
	{
		$form = DepositAdminForm::get();

		if ( ! $form->purse)
		{
			return redirect_admin('deposit_admin');
		}

		return compact('form');
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