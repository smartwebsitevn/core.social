<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class Country_mod extends MY_Mod

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
	 * Lấy tên lục địa
	 * @param  [type] $row     [description]
	 * @param  [type] $regions [description]
	 * @return [type]          [description]
	 */
	public function region($row, $regions = null)
	{
		if(! $row->group_id )
			return $row;

		if( $regions )
		{
			$region = objectExtract( array( 'id' => $row->group_id ), $regions, true );
		}
		else
		{
			$region = model('country_group')->get_info( $row->group_id );
		}

		$row->_group = $region->name;
		return $row;
	}

}