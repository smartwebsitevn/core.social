<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class message_mod extends MY_Mod
{
	/**
	 * Them cac thong tin phu
	 * 
	 * @param object $row
	 * @return object
	 */
	public function add_info($row)
	{
		$row = parent::add_info($row);
		foreach (array('title') as $p)
		{
			if (isset($row->$p))
			{
				$row->$p = html_escape($row->$p);
			}
		}

		if (isset($row->content))
		{
			$row->content = handle_content($row->content, 'output');
		}
		
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
			case 'view':
			case 'del':
			{
				return TRUE;
			}
			
			
		}
		
		return $result;
	}
}
	
