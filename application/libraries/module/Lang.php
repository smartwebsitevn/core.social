<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Module Lang Class
 * 
 * Class xu ly lang cua module
 * 
 * @author		***
 * @version		2013-09-28
 */
class Module_lang {
	
	// Doi tuong cua module hien tai
	var $MD = '';
	
	// Danh sach gia tri cua cac file lang da load
	protected $_lang = array();
	
	// Danh sach cac file lang da load
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
	 * Load lang file
	 * @param string $file		Ten file lang
	 * @param string $idiom		Ten thu muc lang
	 */
	public function load($file, $idiom = '')
	{
		// Neu load danh sach lang
		if (is_array($file))
		{
			foreach ($file as $babe)
			{
				$this->load($babe, $idiom);
			}
			return;
		}
		
		// Xu ly lang
		if ( ! $file)
		{
			return;
		}
		
		// Neu lang da duoc load truoc do
		if (isset($this->_is_loaded[$file]))
		{
			return;
		}
		
		// Xu ly thu muc lang
		if ($idiom == '')
		{
			// Lay thu muc mac dinh
			$CI =& get_instance();
			$idiom = $CI->config->item('language');
			$idiom = ( ! $idiom) ? 'english' : $idiom;
			
			// Neu thu muc khac english va khong ton tai file thi gan thu muc la english
			$file_path = APPPATH.'modules/'.$this->MD->key.'/language/'.$idiom.'/'.$file.EXT;
			if ($idiom != 'english' && ! file_exists($file_path))
			{
				$idiom = 'english';
			}
		}
		
		// Neu khong ton tai file
		$file_path = APPPATH.'modules/'.$this->MD->key.'/language/'.$idiom.'/'.$file.EXT;
		if ( ! file_exists($file_path))
		{
			show_error('Unable to load the requested file: '.$file_path);
		}
		
		// Load file
		include($file_path);
		
		// Neu khong ton tai lang
		if ( ! isset($lang) || ! is_array($lang))
		{
			show_error('Language file contains no data: '.$file_path);
		}
		
		// Luu lang
		$this->_lang = array_merge($this->_lang, $lang);
		
		// Luu file vao danh sach da load
		$this->_is_loaded[$file] = TRUE;
		unset($lang);
	}
	
	// --------------------------------------------------------------------

	/**
	 * Lay lang
	 *
	 * @param  string  		$key
	 * @param  array|string $replace
	 * @return string
	 */
	public function line($key, $replace = array())
	{
		$key = (string)$key;
	
		$line = array_get($this->_lang, $key, $key);
		
		$args = func_get_args();
		$args[0] = $line;
	
		return call_user_func_array(array(t('lang'), 'make_line'), $args);
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Kiem tra lang line co ton tai hay khong
	 * @param string $line		Line muon kiem tra
	 */
	public function line_exists($line)
	{
		return (isset($this->_lang[$line])) ? TRUE : FALSE;
	}
	
}
