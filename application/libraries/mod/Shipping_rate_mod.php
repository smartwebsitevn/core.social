<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class Shipping_rate_mod extends MY_Mod

{

	

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
		$row->_url_view = site_url("{$name}-shipping-rate-{$row->id}");
		
		return $row;
	}

	

}