<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
	/**
	 * Fetch an item from the COOKIE array
	 */
	function get_cookie($index = '', $xss_clean = TRUE)
	{
		$CI =& get_instance();

		$prefix = '';

		if ( ! isset($_COOKIE[$index]) && config_item('cookie_prefix') != '')
		{
			$prefix = config_item('cookie_prefix');
		}

		return $CI->input->cookie($prefix.$index, $xss_clean);
	}
	