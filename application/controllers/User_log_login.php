<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_log_login extends MY_Controller
{
	/**
	 * Ham khoi dong
	 */
	public function __construct()
	{
		parent::__construct();

		if ( ! user_is_login())
		{
			redirect_login_return();
		}
		
		$this->lang->load('site/user');
	}
	
	/**
	 * Home
	 */
	public function index()
	{
		$user = user_get_account_info();
		
		$this->_list([
			
			'mod' => 'log',
			
			'filter' => true,
			
			'filter_fields' => ['created', 'created_to'],
			
			'filter_value' => [
				'table' 	=> 'user',
				'table_id' 	=> $user->id,
				'action' 	=> 'login',
			],
			
		]);
	}
}
