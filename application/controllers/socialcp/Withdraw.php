<?php

use App\Withdraw\WithdrawFactory as WithdrawFactory;
use App\Withdraw\Model\WithdrawModel as WithdrawModel;

class Withdraw extends MY_Controller
{
	/**
	 * Khoi tao doi tuong
	 */
	public function __construct()
	{
		parent::__construct();

		t('lang')->load('modules/withdraw/common');
	}

	/**
	 * Home
	 */
	public function index()
	{
		redirect_admin('invoice_order');
	}

	/**
	 * Hoan thanh
	 */
	public function complete()
	{
		$this->_dispatchAction('complete', function(WithdrawModel $withdraw)
		{
			WithdrawFactory::withdraw()->complete($withdraw);
		});
	}

	/**
	 * Huy bo
	 */
	public function cancel()
	{
		$this->_dispatchAction('cancel', function(WithdrawModel $withdraw)
		{
			WithdrawFactory::withdraw()->cancel($withdraw);
		});
	}

	/**
	 * Thuc hien action
	 *
	 * @param string   $action
	 * @param callable $handle
	 */
	protected function _dispatchAction($action, callable $handle)
	{
		$this->_action_model_handler([
			'model'  => 'App\Withdraw\Model\WithdrawModel',
			'action' => $action,
			'handle' => $handle,
		]);
	}

}