<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class emailsend_to_mod extends MY_Mod
{
	/**
	 * Them cac thong tin phu
	 *
	 * @param object $row
	 * @return object
	 */
	public function add_info($row)
	{

		if (isset($row->status))
		{
			$row->_status = ($row->status) ? 'on' : 'off';
		}
		
		return $row;
	}
	

}