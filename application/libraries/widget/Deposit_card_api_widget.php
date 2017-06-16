<?php
class Deposit_card_api_widget extends MY_Widget
{
	/**
	 * Khoi tao doi tuong
	 */
	public function __construct()
	{
		t('lang')->load('site/deposit_card_api');
	}
	
	/*
	 * Hien thi link nap rut gon
	 */
	function view()
	{
	    $user = '';
	    if(user_is_login())
	    {
	        $user = user_get_account_info();
	    }
	    $this->data['user']  = $user;
	    
	    // Hien thi view
	    $this->load->view('tpl::deposit_card_api/view', $this->data);
	}
}