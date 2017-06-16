<?php

use App\Withdraw\Handler\Form\WithdrawAdmin\WithdrawAdminFormRequest;
use App\Withdraw\Handler\Form\WithdrawAdmin\WithdrawAdminFormHandler;

class Withdraw_admin extends MY_Controller
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

		$request = new WithdrawAdminFormRequest($input);

		$form = new WithdrawAdminFormHandler($request);

		$this->_run_form_handler($form, $page);
	}

}