<?php

use App\Deposit\Handler\Form\DepositPayment\DepositPaymentFormHandler;
use App\Deposit\Handler\Form\DepositPayment\DepositPaymentFormRequest;

class Deposit extends MY_Controller
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

		$request = new DepositPaymentFormRequest($input);

		$form = new DepositPaymentFormHandler($request);

		$this->_run_form_handler($form, $page);
	}

}