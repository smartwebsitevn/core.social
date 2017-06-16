<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

use App\Transaction\Handler\Request\TranView;
use App\Transaction\Model\TranModel as TranModel;

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
		if ( ! user_is_login())
		{
			redirect_login_return();
		}

		$user = user_get_account_info();

		$this->_list([
			'filter' => true,
			'filter_fields' => [
				'id', 'invoice_id', 'status', 'payment_id', 'payment_tran_id', 'created', 'created_to',
			],
			'filter_value' => [
				'user_id' => $user->id,
			],
			'input' => ['relation' => 'invoice'],
			'actions' => ['view'],
			'display' => false,
		]);

		$this->data['list'] = TranModel::makeCollection($this->data['list']);

		$this->_display();
	}

	/**
	 * View
	 */
	public function view()
	{
		$input = array_merge(t('input')->get(), [
			'tran_id' => t('uri')->rsegment(3),
		]);

		$response = (new TranView($input))->handle();

		$this->data = array_merge($this->data, $response);

		$this->_display();
	}
}