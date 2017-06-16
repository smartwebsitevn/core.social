<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Card_history_mod extends MY_Mod
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
		
		if (isset($row->date))
		{
			$row->_date = get_date($row->date);
		}
		
		return $row;
	}
	
	/**
	 * Tao filter tu input
	 * 
	 * @param array $fields
	 * @param array $input
	 * @return array
	 */
	public function create_filter(array $fields, &$input = array())
	{
		// Lay gia tri cua filter dau vao
		$input = array();
		foreach ($fields as $f)
		{
			$v = $this->input->get($f);
			$v = security_handle_input($v, in_array($f, array()));
			
			$input[$f] = $v;
		}
		
		if ( ! empty($input['id']))
		{
			foreach ($input as $f => $v)
			{
				$input[$f] = ($f != 'id') ? '' : $v;
			}
		}
		
		// Tao bien filter
		$filter = array();
		$query 	= url_build_query($input, TRUE);
		foreach ($query as $f => $v)
		{
			switch ($f)
			{
				case 'date':
				{
					$created_to = $input['date_to'];
					$v = (strlen($created_to)) ? array($v, $created_to) : $v;
					$v = get_time_between($v);
					$v = ( ! $v) ? NULL : $v;
					break;
				}
			}
			
			if (is_null($v)) continue;
			
			$filter[$f] = $v;
		}
		
		return $filter;
	}
	
}