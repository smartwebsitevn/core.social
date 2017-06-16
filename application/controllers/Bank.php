<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bank extends MY_Controller
{
	/**
	 * Ham khoi dong
	 */
	public function __construct()
	{
		parent::__construct();
		
		$this->lang->load('site/bank');
	}
	
	/**
	 * List
	 */
	public function index()
	{
		page_info('breadcrumbs', array(current_url(), lang('title_bank')));
		
		page_info('title', lang('title_bank'));
		
		$list = array();
		$list['input']['where']['status'] = config('status_on', 'main');
		$this->_list($list);
	}
	
}
