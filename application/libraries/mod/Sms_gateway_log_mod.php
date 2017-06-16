<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sms_gateway_log_mod extends MY_Mod
{
	/**
	 * Lay status id
	 *
	 * @param string $name
	 * @return int|false
	 */
	public function status($name)
	{
		return array_search($name, $this->statuss());
	}
	
	/**
	 * Lay status name
	 *
	 * @param int $id
	 * @return string
	 */
	public function status_name($id)
	{
		return array_get($this->statuss(), $id);
	}
	
	/**
	 * Lay statuss
	 *
	 * @return array
	 */
	public function statuss()
	{
		return ['completed', 'failed'];
	}
	
	/**
	 * Them cac thong tin phu
	 * 
	 * @param object $row
	 * @return object
	 */
	public function add_info($row)
	{
		$row = parent::add_info($row);

		if (isset($row->status))
		{
			$row->_status = $this->status_name($row->status);
		}
		
		if (isset($row->created))
		{
		    $row->_created = get_date($row->created);
		    $row->_created_full = get_date($row->created, 'full');
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
		if ( ! $row) return FALSE;
	
		switch ($action)
		{
			case 'view':
			{
				return true;
			}
			
			case 'active':
			{
				return $row->status == 1;
			}
		}
	
		return parent::can_do($row, $action);
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
		$statuss = $this->statuss();
		
		// Lay gia tri cua filter dau vao
		$input = array();
		foreach ($fields as $f)
		{
			$v = $this->input->get($f);
			$v = security_handle_input($v, in_array($f, array()));

			if (
				($f == 'status' && ! in_array($v, $statuss))
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
				case 'status':
				{
					$v = $this->status($v);
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