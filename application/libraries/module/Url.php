<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Module Url Class
 * 
 * Class xu ly Url cua module
 * 
 * @author		***
 * @version		2014-01-06
 */
class Module_url {
	
	// Doi tuong cua module hien tai
	var $MD = '';
	
	
	// --------------------------------------------------------------------
	
	/**
	 * Ham khoi dong
	 * @param object $MD	Doi tuong cua module
	 */
	public function __construct($MD)
	{
		// Luu doi tuong cua module
		$this->MD = $MD;
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Tao site url
	 */
	public function site($controller,$uri = '', array $opt = array())
	{
		
		return module_url('site',$this->MD->key,$controller,  $uri, $opt);
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Tao admin url
	 */
	public function admin($controller,$uri = '', array $opt = array())
	{
		return module_url( 'admin',$this->MD->key, $controller,$uri, $opt);
	}
	
}
