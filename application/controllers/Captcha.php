<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Captcha extends CI_Controller
{
	/**
	 * Remap method
	 */
	public function _remap($method, $params = array())
	{
		return $this->_create();
	}
	
	/**
	 * Tao captcha
	 */
	protected function _create()
	{
		$this->load->library('captcha_library');
		
		return $this->captcha_library->create();
	}
	
}