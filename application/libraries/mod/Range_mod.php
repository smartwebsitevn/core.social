<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Range_mod extends MY_Mod
{
	/**
	 * Tao url
	 *
	 * @param object $row
	 * @return object
	 */

	//=== Tran Type helper
	function check_range_type($type, $types=NULL)
	{
		// Tai file thanh phan
		if(!$types){
			$types=$this->get_range_types();
		}
		if(!isset($types[$type]))
			return FALSE;
		return	TRUE;//$types[$type];
	}
	/**
	 * Lay danh sach card type
	 */
	public function get_range_types()
	{
		$types = $this->config('range_types');
		return $types;
	}

}