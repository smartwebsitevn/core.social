<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Session extends CI_Session
{
	/**
	 * Lay session id
	 *
	 * @return string
	 */
	public function id()
	{
		return session_id();
	}
	
	/**
	 * Kiem tra session theo ruri. Neu ruri hien tai khac voi ruri khai bao thi se unset session
	 * @param string $key	Key cua session	
	 * @param string $ruri	Ruri can kiem tra
	 */
	public function check_ruri($key, $ruri)
	{
		$ruri = trim($ruri, '/');
		
		$ruri_cur = get_instance()->uri->ruri_string();
		$ruri_cur = trim($ruri_cur, '/');
		
		if ($ruri_cur != $ruri && $this->userdata($key))
		{
			$this->unset_userdata($key);
		}
	}
	
}