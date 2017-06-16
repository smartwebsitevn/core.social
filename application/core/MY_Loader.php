<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Loader extends CI_Loader
{
	/**
	 * Bien luu cac thu vien zend da load
	 * 
	 * @var array
	 */
	protected $_zends = array();
	
	/**
	 * Bien luu ten va doi tuong cua cac class da load
	 * 
	 * @var array
	 */
	protected $_classes = array();
	
	
	/**
	 * Cho phep su dung cac thuoc tinh cua controller
	 */
	public function __get($key)
	{
		return t($key);
	}
	
	/**
	 * Load view
	 * 
	 * @param string $view
	 * @param array  $data
	 * @param bool	 $return
	 */


	public function view($view, $data = array(), $return = false)
	{
		return t('view')->load($view, $data, $return);
	}
	
	/**
	 * Tai cac library cua zend
	 * 
	 * @param string $library 	Duong dan den thu muc cua library
	 */
	public function zend($library)
	{
		// Load danh sach library
		if (is_array($library))
		{
			foreach ($library as $babe)
			{
				$this->zend($babe);
			}
			return;
		}
		
		// Neu khong ton tai library
		if ( ! $library)
		{
			return;
		}
		
		// Neu library da duoc load roi
		if (in_array($library, $this->_zends, TRUE))
		{
			return;
		}
		
		// Load library
		$this->_zends[] = $library;
		require_once APPPATH.'libraries/Zend/'.$library.EXT;
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Tai cac class
	 * @param string 	$class		Ten class
	 * @param mixed 	$params		Tham so truyen vao class
	 * @param string 	$name		Ten doi tuong gan cho class
	 */
	public function _class($class, $params = NULL, $name = NULL)
	{
		// Neu load danh sach class
		if (is_array($class))
		{
			foreach ($class as $babe)
			{
				$this->_class($babe);
			}
			return;
		}
		
		// Xu ly class
		$class = trim($class, '\\');
		if ( ! $class)
		{
			return;
		}
		
		// Lay name
		if ( ! $name)
		{
			$name = $class;
		}
		
		// Neu class da duoc load truoc do
		if (isset($this->_classes[$name]))
		{
			return;
		}
		
		// Neu khong ton tai file
		$file = strtolower($class);
		$file = APPPATH.$file.EXT;
		$file = str_replace('\\', '/', $file);

		if ( ! file_exists($file))
		{
			show_error('Unable to load the requested file: '.$file);
		}
	
		// Xu ly class name
		$class = strtolower($class);
		$class = explode('\\', $class);
		foreach ($class as $i => $v)
		{
			$class[$i] = ucfirst($v);
		}
		$class = implode('\\', $class);

		// Neu khong ton tai class
		require_once($file);
		if ( ! class_exists($class))
		{
			show_error('Non-existent class: '.$class);
		}
		// 	 echo '<br>2class:';	  pr($file,false);
		// Goi class
		if ($params !== NULL)
		{
			$this->_classes[$name] = new $class($params);

		}
		else
		{
			$this->_classes[$name] = new $class;
		}
        // pr(	$this->_classes[$name]);
	}
	
	/**
	 * Lay doi tuong cua class
	 * @param string $class		Ten class
	 */
	public function get_class($class = '')
	{
		if ($class === '')
		{
			return $this->_classes;
		}
		
		return (isset($this->_classes[$class])) ? $this->_classes[$class] : FALSE;
	}
	
}