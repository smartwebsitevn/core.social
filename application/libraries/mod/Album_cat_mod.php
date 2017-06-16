<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Album_cat_mod extends MY_Mod
{
	/**
	 * @param object $row
	 * @param bool|false $full_info
	 * @return object
	 */
	function add_info($row,$full_info=false)
	{

		$row = parent::add_info($row);
		$image_name = (isset($row->image_name)) ? $row->image_name : '';
		$row->image = file_get_image_from_name($image_name,public_url('img/no_image.png'));

		$row = $this->url($row);
		return $row;
	}
	/**
	 * Tao url
	 *
	 * @param object $row
	 * @return object
	 */
	public function url($row)
	{
		//$name = url_title(convert_vi_to_en($row->name));
		//$row->_url_view = site_url("job/cat/{$row->id}/{$name}");
		$row->_url_view = site_url("llbum/cat/{$row->id}");

		return $row;
	}


}