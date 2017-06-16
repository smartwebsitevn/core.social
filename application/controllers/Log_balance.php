<?php

use App\Accountant\Model\LogBalanceModel;
use App\User\UserFactory;

class Log_balance extends MY_Controller
{
	/**
	 * Khoi tao doi tuong
	 */
	public function __construct()
	{
		parent::__construct();

		if ( ! UserFactory::auth()->logged())
		{
			redirect_login_return();
		}

		t('lang')->load('modules/accountant/common');
	}

	/**
	 * List
	 */
	public function index()
	{
		$user = UserFactory::auth()->user();

		$purse_id = (int) t('input')->get('purse_id') ?: $user->purses->first()->id;

		$this->_list([
			'filter' => true,
			'filter_fields' => ['created', 'created_to'],
			'filter_value' => [
				'user_id'  => $user->id,
				'purse_id' => $purse_id,
			],
			'input' => ['relation' => 'purse'],
			'actions' => [],
			'actions_list' => [],
			'display' => false,
		]);

		$this->data['list'] = LogBalanceModel::makeCollection($this->data['list']);
		$this->data['user'] = $user;
		$this->data['filter']['purse_id'] = $purse_id;

		$this->_display();
	}
}