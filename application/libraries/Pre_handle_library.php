<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Pre Handle Library Class
 * 
 * @author		***
 * @version		2015-04-03
 */

// --------------------------------------------------------------------

/**
 * Pre Handle Library
 */
class Pre_handle_library
{
	/**
	 * Goi driver
	 */
	public function __get($key)
	{
		return t('lib')->driver('pre_handle', $key);
	}
	
}

// --------------------------------------------------------------------

/**
 * Pre Handle Base
 */
class MY_Pre_handle
{
	/**
	 * Cho phep su dung cac thuoc tinh cua controller
	 */
	public function __get($key)
	{
		return t($key);
	}
	
}
