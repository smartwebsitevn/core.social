<?php

use App\Payment\PaymentFactory;
use App\Payment\Model\PaymentModel as PaymentModel;
use App\Payment\Handler\Form\Payment as PaymentFormHandler;

class Payment extends MY_Controller
{
	/**
	 * Khoi tao doi tuong
	 */
	public function __construct()
	{
		parent::__construct();

		t('lang')->load('modules/payment/payment');
	}

	/**
	 * Chon Paygate khi add payment
	 */
	public function add_select()
	{
		$this->data = array_merge($this->data, [
			'paygates' => PaymentFactory::paygateManager()->listActive(),
			'url_add'  => $this->_url('add'),
		]);

		$this->_display();
	}

	/**
	 * Them moi
	 */
	public function add()
	{
		$paygate_key = t('input')->get('paygate');

		if ( ! PaymentFactory::paygateManager()->isActive($paygate_key))
		{
			$this->_redirect('add_select');
		}

		$payment = new PaymentModel(compact('paygate_key'));

		$this->_make_form($payment);
	}

	/**
	 * Chinh sua
	 */
	public function edit()
	{
		$id = t('uri')->rsegment(3);

		$payment = PaymentModel::find($id);

		if ( ! $payment || ! $payment->can('edit'))
		{
			set_message(lang('notice_can_not_do'));

			$this->_redirect();
		}

		$this->_make_form($payment);
	}

	/**
	 * Tao form xu ly
	 *
	 * @param PaymentModel $payment
	 */
	protected function _make_form(PaymentModel $payment)
	{
		$form = new PaymentFormHandler($payment);

		$this->_run_form_handler($form);
	}

	/**
	 * Xoa
	 */
	public function delete()
	{
		$this->_action_handler(function($ids)
		{
			foreach ((array) $ids as $id)
			{
				$payment = PaymentModel::find($id);

				if ( ! $payment || ! $payment->can('delete')) continue;

				PaymentFactory::payment()->delete($payment);
			}

			set_message(lang('notice_update_success'));
		});
	}

	/**
	 * Home
	 */
	public function index()
	{
		$this->_list([
			'page'    => false,
			'sort'    => true,
			'display' => false,
		]);

		$this->data['list'] = PaymentModel::makeCollection($this->data['list']);

		$this->_display();
	}

}