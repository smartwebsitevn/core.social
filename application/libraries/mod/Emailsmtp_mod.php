<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class emailsmtp_mod extends MY_Mod
{
	/**
	 * Them cac thong tin phu s
	 *
	 * @param object $row
	 * @return object
	 */
	public function add_info($row)
	{

		if (isset($row->active))
		{
			$row->_active = ($row->active) ? 'on' : 'off';
		}
		
		return $row;
	}
	

}