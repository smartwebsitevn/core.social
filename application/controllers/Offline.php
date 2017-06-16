<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Offline extends MY_Controller
{
	/**
	 * Trang chinh
	 */
	public function index()
	{
		$this->data['notice'] = setting_get('config-maintenance_notice');
		
		$this->_display('', null);
	}
	
}
