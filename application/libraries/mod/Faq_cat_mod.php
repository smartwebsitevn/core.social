<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Faq_cat_mod extends MY_Mod {
	
	/**
	 * Tao url
	 * 
	 * @param object $row
	 * @return object
	 */
	public function url($row)
	{
		$name = url_title(convert_vi_to_en($row->name));
		
		$row->_url_view = site_url("faq/cat/{$row->id}/{$name}");
		
		return $row;
	}
	
}