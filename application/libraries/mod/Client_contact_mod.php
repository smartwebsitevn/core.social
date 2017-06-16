<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Client_contact_mod extends MY_Mod
{
	/**
	 * Lay danh sach cac bien contact
	 *
	 * @return array
	 */
	public function get_params()
	{
		return array('email', 'name', 'phone');
	}
	
	/**
	 * Tao contact tuong ung voi user
	 *
	 * @param int|array $user
	 * @return array
	 */
	public function make_user($user)
	{
		if (is_numeric($user))
		{
			$user = t('model')->user->get_info($user);
		}
	
		$contact = array();
		foreach ($this->get_params() as $p)
		{
			$contact[$p] = (string) array_get((array) $user, $p);
		}
		
		return $contact;
	}
	
	/**
	 * Lay contact
	 * 
	 * @return array
	 */
	public function get()
	{
		$data = $this->cookie_get();
		$data = ( ! is_array($data)) ? array() : $data;
		
		foreach ($this->get_params() as $p)
		{
			$data = array_add($data, $p, '');
		}
		
		return $data;
	}
	
	/**
	 * Lay contact tu input
	 * 
	 * @param array $input
	 * @param bool 	$update_cookie	Co cap nhat vao cookie hay khong
	 * @return array
	 */
	public function get_input(array $input, $update_cookie = TRUE)
	{
		$input = array_filter($input);
		$input = array_only($input, $this->get_params());
		
		$data = $this->get();
		$data = array_merge($data, $input);
		
		if ($update_cookie)
		{
			$this->cookie_set($data);
		}
		
		return $data;
	}

	// --------------------------------------------------------------------

	/**
	 * Lay cookie
	 */
	protected function cookie_get()
	{
		return @unserialize(get_cookie($this->cookie_name()));
	}

	/**
	 * Gan cookie
	 * 
	 * @param mixed $data
	 */
	protected function cookie_set($data)
	{
		return set_cookie($this->cookie_name(), serialize($data), config('cookie_expire', 'main'));
	}

	/**
	 * Xoa cookie
	 */
	protected function cookie_del()
	{
		return delete_cookie($this->cookie_name());
	}
	
	/**
	 * Lay ten cookie
	 * 
	 * @return string
	 */
	protected function cookie_name()
	{
		return 'client_contact';
	}
	
}