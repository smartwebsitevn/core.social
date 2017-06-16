<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Card_code_mod extends MY_Mod {
	
	/**
	 * Lay danh sach cac bien
	 * 
	 * @return array
	 */
	public function get_params()
	{
		return array('code', 'serial', 'expiry');
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
			case 'sell':
			{
				return ($row->sell == config('verify_no', 'main'));
			}
			
			case 'unsell':
			{
				return ($row->sell == config('verify_yes', 'main'));
			}
		}
		
		return $result;
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
		// Lay config
		$verify = config('verify', 'main');
		
		// Lay gia tri cua filter dau vao
		$input = array();
		foreach ($fields as $f)
		{
			$v = $this->input->get($f);
			$v = security_handle_input($v, in_array($f, array()));
			
			if (
				($f == 'sell' && ! in_array($v, $verify))
			)
			{
				$v = '';
			}
				
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
				case 'sell':
				{
					$v = config("verify_{$v}", 'main');
					break;
				}
				
				case 'created':
				{
					$created_to = $input['created_to'];
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