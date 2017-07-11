<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Input Core Class
 *
 * Class xu ly template
 *
 * @author		***
 * @version		2015-04-16
 */
class MY_Input extends CI_Input {

	function _fetch_from_array(&$array, $index = '', $xss_clean = TRUE)
	{
		return parent::_fetch_from_array($array, $index, $xss_clean);
	}
	
	function get($index = NULL, $xss_clean = TRUE)
	{
		return parent::get($index, $xss_clean);
	}
	
	function post($index = NULL, $xss_clean = TRUE)
	{
		return parent::post($index, $xss_clean);
	}
	
	function get_post($index = '', $xss_clean = TRUE)
	{
		return parent::get_post($index, $xss_clean);
	}
	
	function cookie($index = '', $xss_clean = TRUE)
	{
		return parent::cookie($index, $xss_clean);
	}
	
	function server($index = '', $xss_clean = TRUE)
	{
		return parent::server($index, $xss_clean);
	}
	
	public function get_request_header($index, $xss_clean = TRUE)
	{
		return parent::get_request_header($index, $xss_clean);
	}
	
	/**
	 * Kiem tra moi truong mobile
	 * 
	 * @return boolean
	 */

	public function view2($view, $data = array(), $return = false)
	{
		$host= $_host= $_SERVER['HTTP_HOST'];
		$host =  explode('.',$host);
		//  pr($host);
		if (
		(t('agent')->is_mobile() || isset(t('uri')->uri->mobile) || $host[0] =='m')     //&& 	is_dir(APPPATH.'views/site_mobile')
		)
		{
			// Xu ly controller
			$controller = t('uri')->segment(1);
			$controller = strtolower($controller);
			if ($controller != config('admin_folder', 'main'))
			{
				$view_tmp = preg_replace('#^site/#i', '', $view);
				$file_view = APPPATH . 'views/site_mobile/' . $view_tmp  . '.php';
				// echo  '<br> == check: '. $file_view;
				if (file_exists ($file_view )) {
					$view = preg_replace('#^site/#i', 'site_mobile/', $view);
				}

			}
		}
		return t('view')->load($view, $data, $return);
	}

	public function is_mobile()
	{
		/*static $device=null;
		if($device)
			return $device;*/

		$device = t('session')->userdata('device');
		if ($device)
		{
			return ($device == 'mobile');
		}

		if( t('lib')->make('user_agent')->is_mobile())
			return true;

		// kiem tra sub domain
		$host=  $_SERVER['HTTP_HOST'];
		$host =  explode('.',$host);
		if ($host[0] =='m')
			return true;

		// moi truong test
		if(	! empty(t('uri')->mobile)     )
			return true;

		return false;
	}
	
}

