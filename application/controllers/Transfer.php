<?php

use App\Transfer\Handler\Form\Transfer\TransferFormRequest;
use App\Transfer\Handler\Form\Transfer\TransferFormHandler;
use App\Purse\PurseFactory as PurseFactory;

class Transfer extends MY_Controller
{
	/**
	 * Khoi tao doi tuong
	 */
	public function __construct()
	{
		parent::__construct();

		if ( ! user_is_login())
		{
			redirect_login_return();
		}

		t('lang')->load('modules/transfer/common');
	}

	/**
	 * Form
	 */
	public function index()
	{
		$this->_makeForm('form');
	}

	/**
	 * Confirm
	 */
	public function confirm()
	{
		$this->_makeForm('confirm');
	}

	/**
	 * Tao form xu ly
	 *
	 * @param string $page
	 */
	public function _makeForm($page)
	{
		$input = t('input')->post();
		$input['page'] = $page;

		$request = new TransferFormRequest($input);

		$form = new TransferFormHandler($request);

		$this->_run_form_handler($form, $page);
	}

	/**
	 * Api user name
	 */
	public function api_user_name()
	{
		$purse_number = t('input')->post('purse_number');

		$purse = PurseFactory::purse()->findByNumber($purse_number);
		$purse = $purse ?: PurseFactory::purse()->findByUserKey($purse_number);

		if ( ! $purse)
		{
			return set_output('json', json_encode([
				'status' => false,
			]));
		}

		$result = [
			'status' => true,
			'name'   => $purse->user->name,
		];

		return set_output('json', json_encode($result));
	}
}
