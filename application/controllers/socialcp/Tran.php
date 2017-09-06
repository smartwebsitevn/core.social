<?php

use App\Transaction\Library\TranStatus;
use App\Transaction\TranFactory as TranFactory;
use App\Transaction\Model\TranModel as TranModel;
use App\User\UserFactory;

class Tran extends MY_Controller
{
	/**
	 * Khoi tao doi tuong
	 */
	public function __construct()
	{
		parent::__construct();

		t('lang')->load('modules/transaction/tran');
	}

	/**
	 * List
	 */
	public function index()
	{
		$filter = [];

		$user_key = t('input')->get('user_key');

		if ($user = UserFactory::user()->find($user_key))
		{
			$filter['user_id'] = $user->id;
		}

		$this->_list([
			'filter' => true,
			'filter_fields' => [
				'id', 'invoice_id', 'status', 'payment_id', 'payment_tran_id',
				'currency_id', 'user_id', 'user_ip', 'created', 'created_to',
			],
			'filter_value' => $filter,
			'input' => ['relation' => 'user'],
			'order' => true,
			'order_fields' => ['id', 'amount', 'status', 'payment_id', 'payment_tran_id', 'user_id', 'user_ip', 'created'],
			'actions' => ['view'],
			'actions_list' => [],
			'display' => false,
		]);

		$this->data['list'] = TranModel::makeCollection($this->data['list']);
		$this->data['list_status'] = TranStatus::lists();

		$this->_display();
	}

	/**
	 * View
	 */
	public function view()
	{
		$id = t('uri')->rsegment(3);

		$tran = TranModel::find($id);

		if ( ! $tran)
		{
			set_message(lang('notice_can_not_do'));

			$this->_redirect();
		}

		$payment_tran = TranFactory::tran()->viewPaymentTran($tran);

		$this->data = array_merge($this->data, compact('tran', 'payment_tran'));

		$this->_display();
	}

	/**
	 * Kich hoat
	 */
	public function active()
	{
		$this->_dispatchAction('active', function(TranModel $tran)
		{
			TranFactory::tran()->active($tran);
		});
	}

	/**
	 * Huy bo
	 */
	public function cancel()
	{
		$this->_dispatchAction('cancel', function(TranModel $tran)
		{
			TranFactory::tran()->cancel($tran);
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
			'model'  => 'App\Transaction\Model\TranModel',
			'action' => $action,
			'handle' => $handle,
		]);
	}

}