<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class Form_content_mod
 */
class Form_content_mod extends MY_Mod
{
	function add_info($row,$full_info=false)
	{
		$row = parent::add_info($row);
		// su ly noi dung
		$lang =lang_get_cur();
		$row->_content = model('form_content_content')->get($row->id,$lang->id);

		return $row;
	}
	function check_form_type($type, $types=NULL)
	{
		// Tai file thanh phan
		if(!$types){
			$types=$this->get_form_types();
		}
		if(!isset($types[$type]))
			return FALSE;
		return	TRUE;//$types[$type];
	}
	/**
	 * Lay danh sach
	 */
	public function get_form_types()
	{
		$types = $this->config('form_content_types');
		return $types;
	}

}