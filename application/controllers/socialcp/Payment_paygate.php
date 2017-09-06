<?php

use App\Payment\PaymentFactory as PaymentFactory;
use App\Payment\Model\PayGateModel as PayGateModel;
use App\Payment\Handler\Form\PayGate as PayGateFormHandler;

class Payment_paygate extends MY_Controller
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
	 * Home
	 */
	public function index()
	{
		$this->data = array_merge($this->data, [
			'list_installed'     => PaymentFactory::paygateManager()->listInstalled(),
			'list_not_installed' => PaymentFactory::paygateManager()->listNotInstalled(),
		]);

		$this->_display();
	}

	/**
	 * Cai dat
	 */
	public function install()
	{
		$key = t('uri')->rsegment(3);

		if ( ! PaymentFactory::paygateManager()->canInstall($key))
		{
			set_message(lang('notice_can_not_do'));

			$this->_redirect();
		}

		$paygate = PaymentFactory::paygateManager()->makeInfo($key);

		$this->_make_form($paygate);
	}

	/**
	 * Chinh sua
	 */
	public function edit()
	{
		$id = t('uri')->rsegment(3);

		$paygate = PayGateModel::find($id);

		if ( ! $paygate || ! $paygate->can('edit'))
		{
			set_message(lang('notice_can_not_do'));

			$this->_redirect();
		}

		return $this->_make_form($paygate);
	}

	/**
	 * Tao form xu ly
	 *
	 * @param PayGateModel $paygate
	 */
	protected function _make_form(PayGateModel $paygate)
	{
		$form = new PayGateFormHandler($paygate);

		$this->_run_form_handler($form);
	}

	/**
	 * Go bo
	 */
	public function uninstall()
	{
		$this->_action_handler(function($ids)
		{
			foreach ((array) $ids as $id)
			{
				$paygate = PayGateModel::find($id);

				if ( ! $paygate || ! $paygate->can('uninstall')) continue;

				PaymentFactory::paygate()->uninstall($paygate);
			}

			set_message(lang('notice_update_success'));
		});
	}

	/**
	 * Dong bo thong tin
	 */
	public function sync()
	{
		PaymentFactory::paygate()->sync();

		set_message(lang('notice_update_success'));
	}

}