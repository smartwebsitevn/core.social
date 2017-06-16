<?php namespace App\Withdraw\Handler\Form\WithdrawAdmin;

use App\Admin\AdminFactory as AdminFactory;
use App\Withdraw\Command\WithdrawAdminCommand as WithdrawAdminCommand;
use App\Withdraw\Job\WithdrawAdmin as WithdrawAdminJob;
use Core\App\App;
use Core\FormHandler\FormHandlerInterface;

class WithdrawAdminFormHandler implements FormHandlerInterface
{
	/**
	 * Doi tuong Request
	 *
	 * @var WithdrawAdminFormRequest
	 */
	protected $request;

	/**
	 * Doi tuong Validator
	 *
	 * @var WithdrawAdminFormValidator
	 */
	protected $validator;


	/**
	 * Khoi tao doi tuong
	 *
	 * @param WithdrawAdminFormRequest $request
	 */
	public function __construct(WithdrawAdminFormRequest $request)
	{
		$this->request = $request;

		$this->validator = new WithdrawAdminFormValidator($request);
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
		WithdrawAdminForm::delete();

		$command = new WithdrawAdminCommand([
			'admin'  => AdminFactory::auth()->user(),
			'purse'  => $this->request->getPurse(),
			'amount' => $this->request->get('amount'),
			'desc'   => $this->request->get('desc'),
		]);

		$withdraw = (new WithdrawAdminJob($command))->handle();

		set_message(lang('notice_update_success'));

		return $withdraw->invoice_order->adminUrl('view');
	}

	/**
	 * Submit page form
	 *
	 * @return string
	 */
	protected function submitForm()
	{
		$data = $this->data();

		$form = new WithdrawAdminForm($data);

		$form->save();

		return admin_url('withdraw_admin/confirm');
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
		if ( ! $form = WithdrawAdminForm::get())
		{
			return redirect_admin('withdraw_admin');
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