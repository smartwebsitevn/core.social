<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	/**
	 * Lay danh sach cac the loai cha bao gom ca the loai hien tai
	 */
	function cat_get_parents($cat)
	{
		$parents = array();
		foreach ($cat->_parent as $parent)
		{
			$row = new stdClass();
			$row->id 	= $parent['id'];
			$row->name 	= $parent['name'];
			$parents[] 	= $row;
		}
		
		$row = new stdClass();
		$row->id 	= $cat->id;
		$row->name 	= $cat->name;
		$parents[] 	= $row;
		
		return $parents;
	}
	