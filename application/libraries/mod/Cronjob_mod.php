<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cronjob_mod extends MY_Mod
{
	/**
	 * Them cac thong tin phu
	 * 
	 * @param object $row
	 * @return object
	 */
	public function add_info($row)
	{
		if (isset($row->content))
		{
			$row->content = handle_content($row->content, 'output');
		}
		
		return $row;
	}

	
}