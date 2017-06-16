<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Module Call Controller Class
 * 
 * Goi controller cua module
 * 
 * @author		***
 * @version		2014-01-07
 */
class Md extends MY_Controller {
	
	/**
	 * Remap method
	 */
	public function _remap($module)
	{
	// Goi controller method cua module
		//$method = $this->uri->rsegment(3);
		//$this->module->call_controller($module, 'admin', $method);
		$this->module->call_controller('site',$module);
	}
	
	/**
	 * Callback method
	 */
	public function _($value, $method_args)
	{
		$module 		= $this->uri->rsegment(2);
		$method_args	= explode(',', $method_args, 2);
		$method 		= $method_args[0];
		$args 			= (isset($method_args[1])) ? $method_args[1] : '';
		
		$error 	= '';
		$result = $this->module->call_controller_callback($module, 'site', $method, $value, $args, $error);
		if ( ! $result)
		{
			$this->form_validation->set_message(__FUNCTION__, $error);
			return FALSE;
		}
		
		return TRUE;
	}
	
}