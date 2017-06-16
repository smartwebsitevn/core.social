<?php

use App\Deposit\Handler\Form\DepositAdmin\Request as DepositAdminFormRequest;
use App\Deposit\Handler\Form\DepositAdmin\Factory as DepositAdminFormHandler;

class Deposit_admin extends MY_Controller
{
	/**
	 * Khoi tao doi tuong
	 */
	public function __construct()
	{
		parent::__construct();

		t('lang')->load('modules/deposit/common');
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

		$request = new DepositAdminFormRequest($input);

		$form = new DepositAdminFormHandler($request);

		$this->_run_form_handler($form, $page);
	}

}