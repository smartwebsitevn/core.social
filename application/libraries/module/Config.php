<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Module Config Class
 * 
 * Class xu ly config cua module
 * 
 * @author		***
 * @version		2014-01-04
 */
class Module_config {
	
	// Doi tuong cua module hien tai
	var $MD = '';
	
	// Danh sach gia tri cua cac file config da load
	protected $_config = array();
	
	// Danh sach cac file config da load
	protected $_is_loaded = array();
	
	
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
	 * Load config file
	 * @param 	string 		$file				File config
	 * @param 	boolean 	$use_sections		Gia tri duoc luu rieng theo file
	 * @param 	boolean 	$fail_gracefully	Khong hien thi thong bao loi neu co
	 * @return	boolean		Config duoc tai thanh cong hay khong
	 */
	public function load($file = '', $use_sections = FALSE, $fail_gracefully = FALSE)
	{
		// Neu file da duoc load truoc do
		$file = ($file == '') ? 'config' : $file;
		if (isset($this->_is_loaded[$file]))
		{
			return TRUE;
		}
		
		// Neu khong ton tai file
		$file_path = APPPATH.'modules/'.$this->MD->key.'/config/'.$file.EXT;
		if ( ! file_exists($file_path))
		{
			if ($fail_gracefully)
			{
				return FALSE;
			}
			show_error('Unable to load the requested file: '.$file_path);
		}
		
		include($file_path);
		
		// Neu khong ton tai config
		if ( ! isset($config) || ! is_array($config))
		{
			if ($fail_gracefully)
			{
				return FALSE;
			}
			show_error('Your '.$file_path.' file does not appear to contain a valid configuration array.');
		}
		
		// Luu config
		if ($use_sections)
		{
			if (isset($this->_config[$file]) && is_array($this->_config[$file]))
			{
				$this->_config[$file] = array_merge($this->_config[$file], $config);
			}
			else
			{
				$this->_config[$file] = $config;
			}
		}
		else
		{
			$this->_config = array_merge($this->_config, $config);
		}
		
		// Luu file vao danh sach da load
		$this->_is_loaded[$file] = TRUE;
		unset($config);
		
		return TRUE;
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Lay gia tri cua item trong config
	 * @param string $item		Item muon lay gia tri
	 * @param string $index		Ten file config
	 */
	public function item($item, $index = '')
	{
		// Config chinh
		if ($index == '')
		{
			if ( ! isset($this->_config[$item]))
			{
				log_message('error', "Module: {$this->MD->key} --> Not found config item: {$item}");
				return FALSE;
			}
			
			return $this->_config[$item];
		}
		
		// Config rieng cua $index
		else
		{
			if ( ! isset($this->_config[$index][$item]))
			{
				log_message('error', "Module: {$this->MD->key} --> Not found config item: {$item} in index: {$index}");
				return FALSE;
			}
			
			return $this->_config[$index][$item];
		}
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Gan gia tri cho item trong config
	 * @param string $item		Item muon gan gia tri
	 * @param string $value		Gia tri muon gan
	 * @param string $index		Ten file config
	 */
	public function set_item($item, $value, $index = '')
	{
		// Config chinh
		if ($index == '')
		{
			$this->_config[$item] = $value;
		}
		
		// Config rieng cua $index
		else
		{
			$this->_config[$index][$item] = $value;
		}
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Kiem tra item co ton tai trong config hay khong
	 * @param string $item		Item muon kiem tra
	 * @param string $index		Ten file config
	 */
	public function item_exists($item, $index = '')
	{
		// Config chinh
		if ($index == '')
		{
			return (isset($this->_config[$item])) ? TRUE : FALSE;
		}
		
		// Config rieng cua $index
		else
		{
			return (isset($this->_config[$index][$item])) ? TRUE : FALSE;
		}
	}
	
}
