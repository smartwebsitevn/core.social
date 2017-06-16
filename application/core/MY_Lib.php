<?php

/**
 * Library Loader Class
 *
 * Class xu ly goi cac library
 *
 * @author		***
 * @version		2015-04-03
 */
class MY_Lib
{
	/**
	 * Goi library
	 * 
	 * @param string $key
	 * @return object
	 */
	public function __get($key)
	{
		$name = $key.'_library';
		
		return $this->make($name);
	}
	
	/**
	 * Lay doi tuong cua library
	 * 
	 * @param string $name
	 * @return object
	 */
	public function make($name)
	{
		if ( ! isset(t()->$name))
		{
			t('load')->library($name, null, $name);
		}
		
		return t()->$name;
	}
	
	/**
	 * Lay doi tuong cua library dang driver
	 * 
	 * @param string $type
	 * @param string $name
	 * @return object
	 */
	public function driver($type, $name)
	{
		$name = "{$type}/{$name}_{$type}";

		return $this->make($name);
	}
	
}