<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Partner_mod extends MY_Mod {
	
	/**
	 * Ham khoi dong
	 */
	public function __construct()
	{
		// Tai cac file thanh phan
		$this->load->model('partner_model');
	}
	
	/**
	 * Them cac thong tin phu
	 * 
	 * @param object $row
	 * @return object
	 */
	public function add_info($row)
	{
		if (isset($row->web))
		{
			$row->web = prep_url($row->created);
		}
		
		if (isset($row->created))
		{
			$row->_created = get_date($row->created);
		}
		
		return $row;
	}
	
}