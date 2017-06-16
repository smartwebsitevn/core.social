<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sayus_mod extends MY_Mod
{
	/**
	 * Them cac thong tin phu
	 *
	 * @param object $row
	 * @return object
	 */
	public function add_info($row)
	{
		if (isset($row->say))
		{
			$row->say = handle_content($row->say, 'output');
		}

		if (isset($row->image_name))
		{
			t('load')->helper('file');
			$row->image = file_get_image_from_name($row->image_name, public_url('img/no_image.png'));
		}
		
		if (isset($row->status))
		{
			$row->_status = ($row->status) ? 'on' : 'off';
		}
		
		return $row;
	}
	

}