<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class tag_mod extends MY_Mod
{
	public $name = 'tag';
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
		
		switch ($action)
		{
			case 'feature':
			{
				return TRUE;
			}
			
			case 'feature_del':
			{
				$p = preg_replace('#_del$#i', '', $action);
				return ($row->$p) ? TRUE : FALSE;
			}
		}
		
		return $result;
	}
	/**
	 * Tao url
	 *
	 * @param object $row
	 * @return object
	 */
	public function url($row, $table = '')
	{
		switch($table)
		{
			default :
				$row->_url_view = site_url($table.'/tag-'.convert_vi_to_en($row->name).'-'.$row->id);
				break;
		}

		return $row;
	}
}