<?php namespace App\Invoice\Handler\Request;

use Core\Base\RequestHandler;
use App\Invoice\Model\InvoiceModel as InvoiceModel;
use App\User\UserFactory as UserFactory;

class InvoiceView extends RequestHandler
{
	/**
	 * Data
	 *
	 * @var array
	 */
	protected $data = [];


	/**
	 * Thuc hien xu ly
	 *
	 * @return mixed
	 */
	public function handle()
	{
		try
		{
			$this->validate();
		}
		catch (\Exception $e)
		{
			return $this->error($e->getMessage());
		}

		return $this->success();
	}

	/**
	 * Validate du lieu
	 *
	 * @throws \Exception
	 */
	protected function validate()
	{
		if ( ! $this->getInvoice())
		{
			throw new \Exception(lang('notice_value_not_exist', lang('invoice')));
		}

		if ( ! $this->checkToken())
		{
			throw new \Exception(lang('notice_value_invalid', lang('token')));
		}

		if ( ! $this->checkAccess())
		{
			throw new \Exception(lang('notice_do_not_have_permission'));
		}
	}

	/**
	 * Kiem tra token
	 *
	 * @return bool
	 */
	protected function checkToken()
	{
		return $this->getInvoice()->token('view') === $this->input('token');
	}

	/**
	 * Kiem tra quyen truy cap
	 *
	 * @return bool
	 */
	protected function checkAccess()
	{
		return UserFactory::auth()->checkAccess([
			'user_id' => $this->getInvoice()->user_id,
			'ip'      => $this->getInvoice()->user_ip,
		]);
	}

	/**
	 * Xu ly response error
	 *
	 * @param string $error
	 */
	protected function error($error)
	{
		set_message($error);

		redirect();
	}

	/**
	 * Xu ly response success
	 *
	 * @return array
	 */
	protected function success()
	{
		$invoice = $this->getInvoice();

		$invoice_orders = $invoice->invoice_orders;

		if ($invoice_orders->count() == 1)
		{
			redirect($invoice_orders->first()->url('view'));
		}

		return compact('invoice');
	}

	/**
	 * Lay thong tin invoice
	 *
	 * @return InvoiceModel|null
	 */
	public function getInvoice()
	{
		if ( ! array_key_exists('invoice', $this->data))
		{
			$this->data['invoice'] = InvoiceModel::find($this->input('invoice_id'));
		}

		return $this->data['invoice'];
	}
}