<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends MY_Controller {
	
/*
 * ------------------------------------------------------
 *  Main handle
 * ------------------------------------------------------
 */
	/**
	 * Hien thi cac thong tin tren site
	 */
	function index()
	{

		// Tai cac file thanh phan
		//$this->lang->load('admin/home');
		
		// Luu bien gui den view
		$this->data['url_backup'] 	= admin_url('home/backup');
		
		// Hien thi view
		$this->_display();
	}
	function blank()
	{
		$this->_display();
	}
	/**
	 * Back data
	 */
	function backup()
	{
		$this->load->library('curl_library');
		
		$url = site_url('cronjob/backup');
		echo $this->curl_library->get($url);
	}
	
	/**
	 * Admin logout
	 */
	function logout()
	{

		admin_logout();
		redirect_admin();
	}
	/**
	 * Thay doi ngon ngu
	 */
	public function lang()
	{
		// Lay currency_id
		$id = $this->uri->rsegment(3);
		$id = ( ! is_numeric($id)) ? 0 : $id;
		// Luu cookie xac nhan lang da duoc cap nhat
		if($id)
			set_cookie('admin_lang', $id, config('cookie_expire', 'main'));
		redirect_admin();
	}
}