<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class System_mod extends MY_Mod
{
	/**
	 * Bien luu data hien tai
	 *
	 * @var array
	 */
	protected $data = array();
	
	
	/**
	 * Lay data
	 *
	 * @param string $key
	 * @param mixed  $default
	 * @return mixed
	 */
	public function get_data($key, $default = null)
	{
		return array_get($this->data, $key, $default);
	}
	
	/**
	 * Lay data
	 *
	 * @param  string  $key
	 * @param  mixed   $value
	 * @return array
	 */
	public function set_data($key, $value)
	{
		return array_set($this->data, $key, $value);
	}
	
}