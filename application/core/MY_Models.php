<?php

/**
 * Model Loader Class
 *
 * Class xu ly goi cac model
 *
 * @author		***
 * @version		2015-04-03
 */
class MY_Models
{
	/**
	 * Goi model
	 * 
	 * @param string $name
	 * @return object
	 */
	public function __get($key)
	{
		$name = $key.'_model';
		
		return $this->make($name);
	}
	
	/**
	 * Lay doi tuong cua model
	 * 
	 * @param string $name
	 * @return object
	 */
	public function make($name)
	{
		if ( ! isset(t()->$name))
		{
			t('load')->model($name, $name);
		}
		
		return t()->$name;
	}
	
}