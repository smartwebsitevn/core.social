<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Slider_item_mod extends MY_Mod {

	/**
	 * Them cac thong tin phu
	 *
	 * @param object $row
	 * @return object
	 */
	public function add_info($row)
	{
		if (isset($row->image_name))
		{
			$row->image = file_get_image_from_name($row->image_name, public_url('img/no_image.png'));
		}

		if ( isset($row->url) && $row->url)

		{
			$row->url = handle_content($row->url, 'output');
			$row->url = prep_url($row->url);
		}
		if ( isset($row->target) && $row->target)
		{
			$row->target = handle_content($row->target, 'output');
		}
		if ( isset($row->desc) && $row->desc)
		{
			$row->desc = handle_content($row->desc, 'output');
		}
		return $row;
	}
}