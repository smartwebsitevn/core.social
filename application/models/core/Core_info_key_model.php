<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Model Core Info Key Class
 * 
 * Class model xu ly thong tin theo key
 *
 * @author		***
 * @version		2015-05-14
 */
class Core_info_key_model extends MY_Model
{
	/**
	 * Kiem tra ton tai
	 * 
	 * @param string $key
	 * @return boolean
	 */
	public function has($key)
	{
		return $this->get_info($key, $this->key) ? true : false;
	}
	
	/**
	 * Luu thong tin
	 * 
	 * @param string $key
	 * @param array $data
	 */
	public function set($key, array $data)
	{
		$data = $this->handle_data_input($data);
		
		if ($this->has($key))
		{
			$this->update($key, $data);
		}
		else 
		{
			$data[$this->key] = $key;
			
			$this->create($data);
		}
	}
	
	/**
	 * Lay thong tin
	 * 
	 * @param string $key
	 * @param string $field
	 * @return object|false
	 */
	public function get($key, $field = '')
	{
		$data = $this->get_info($key, $field);
		
		if ($data)
		{
			$data = (object) $this->handle_data_output((array) $data);
		}
		
		return $data;
	}
	
}
