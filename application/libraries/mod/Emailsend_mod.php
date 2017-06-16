<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class emailsend_mod extends MY_Mod
{
	/**
	 * Them cac thong tin phu s
	 *
	 * @param object $row
	 * @return object
	 */
	public function add_info($row)
	{

		if (isset($row->created))
		{
			$row->_created = get_date($row->created);
			$row->_created_time = get_date($row->created,'time');
		}
		if (isset($row->updated))
		{
			$row->_updated = get_date($row->updated);
			$row->_updated_time = get_date($row->updated,'time');
		}
		
		return $row;
	}
	

}