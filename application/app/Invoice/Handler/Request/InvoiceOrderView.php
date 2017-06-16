<?php namespace App\Invoice\Handler\Request;

use App\Invoice\Model\InvoiceOrderModel as InvoiceOrderModel;
use App\User\UserFactory as UserFactory;
use Core\Base\RequestHandler;

class InvoiceOrderView extends RequestHandler
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
		if ( ! $this->getInvoiceOrder())
		{
			throw new \Exception(lang('notice_value_not_exist', lang('invoice_order')));
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
		return $this->getInvoiceOrder()->token('view') === $this->input('token');
	}

	/**
	 * Kiem tra quyen truy cap
	 *
	 * @return bool
	 */
	protected function checkAccess()
	{
		return UserFactory::auth()->checkAccess([
			'user_id' => $this->getInvoiceOrder()->user_id,
			'ip'      => $this->getInvoiceOrder()->user_ip,
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
		$invoice_order = $this->getInvoiceOrder();
		$invoice_order_view = $invoice_order->invoiceServiceInstance()->view($invoice_order);
		return compact('invoice_order', 'invoice_order_view');
	}

	/**
	 * Lay thong tin invoice_order
	 *
	 * @return InvoiceOrderModel|null
	 */
	public function getInvoiceOrder()
	{
		if ( ! array_key_exists('invoice_order', $this->data))
		{
			$this->data['invoice_order'] = InvoiceOrderModel::find($this->input('invoice_order_id'));
		}

		return $this->data['invoice_order'];
	}
}