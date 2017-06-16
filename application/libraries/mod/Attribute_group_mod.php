<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Attribute_group_mod extends MY_Mod
{
	/**
	 * Them cac thong tin phu
	 * 
	 * @param object $row
	 * @return object
	 */

	public function add_info($row)
	{
		
		return $row;
	}

	

	/**
	 * Kiem tra co the thuc hien hanh dong hay khong
	 * 
	 * @param object $row
	 * @param string $action
	 * @return boolean
	 */

	public function can_do($row, $action)
	{
		$result = parent::can_do($row, $action);

		return $result;
	}

	/**
	 * Tao url
	 *
	 * @param object $row
	 * @return object
	 */

	public function url($row)
	{
		$name = url_title(convert_vi_to_en($row->name));
		$row->_url_view = site_url("{$name}-attribute-group{$row->id}");
		return $row;
	}

}