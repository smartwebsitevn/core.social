<?php

use App\Accountant\Model\LogBalanceModel;
use App\Purse\PurseFactory;
use App\User\UserFactory;

class Log_balance extends MY_Controller
{
	/**
	 * Khoi tao doi tuong
	 */
	public function __construct()
	{
		parent::__construct();

		t('lang')->load('modules/accountant/common');
	}

	/**
	 * List
	 */
	public function index()
	{
		$filter = [];

		$user_key = t('input')->get('user_key');
		$purse_key = t('input')->get('purse_key');

		if ($user = UserFactory::user()->find($user_key))
		{
			$filter['user_id'] = $user->id;
		}

		if ($purse = PurseFactory::purse()->find($purse_key))
		{
			$filter['purse_id'] = $purse->id;
		}

		$this->_list([
			'filter' => true,
			'filter_fields' => ['purse_id', 'purse_key', 'user_id', 'user_key', 'currency_id', 'ip', 'created', 'created_to'],
			'filter_value' => $filter,
			'input' => ['relation' => ['purse', 'user']],
			'actions' => [],
			'actions_list' => [],
			'display' => false,
		]);

		$this->data['list'] = LogBalanceModel::makeCollection($this->data['list']);

		$this->_display();
	}
}