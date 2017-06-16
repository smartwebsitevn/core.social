<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Refund_mod extends MY_Mod
{
	/**
	 * Them cac thong tin phu
	 * 
	 * @param object $row
	 * @return object
	 */
	public function add_info($row)
	{
		$row->status 	= mod('order')->status('completed');
		$row->_status 	= 'completed';
		
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
		$row->status = mod('order')->status('completed');
		
		return mod('order')->can_do($row, $action);
	}
	
	/**
	 * Thuc hien hanh dong
	 * 
	 * @param object|int $row
	 * @param string $action
	 * @return boolean
	 */
	public function action($row, $action)
	{
		if (is_numeric($row))
		{
			$row = $this->_model()->get_info($row);
		}
		
		if ( ! $this->can_do($row, $action))
		{
			return FALSE;
		}
		
		$row = $this->add_info($row);
		
		switch ($action)
		{
			// Xoa don hang
			case 'del':
			{
				$this->_model()->del($row->id);
				
				break;
			}
			
			// Lay thong tin
			case 'get':
			{
				return $row;
				
				break;
			}
		}
		
		return TRUE;
	}
	
}