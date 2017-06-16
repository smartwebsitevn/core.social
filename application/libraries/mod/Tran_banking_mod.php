<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tran_banking_mod extends MY_Mod
{
	/**
	 * Lay setting
	 * 
	 * @param string 	$key
	 * @param mixed 	$default
	 * @return mixed
	 */
	public function setting($key = null, $default = null)
	{
		$setting = module_get_setting($this->_get_mod());
		
		return array_get($setting, $key, $default);
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
			$row->_status = mod('tran')->status_name($row->status);
		}
		
		foreach (array('amount') as $p)
		{
			if (isset($row->$p))
			{
				$row->$p = (float) $row->$p;
				$row->{'_'.$p} = currency_format_amount_default($row->$p);
			}
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
		// Lay config
		$statuss = mod('tran')->statuss();
		
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
					$v = mod('tran')->status($v);
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
	
	/**
	 * Tao content transfer
	 * 
	 * @param int $id
	 * @return string
	 */
	public function make_content_transfer($id)
	{
		$content = $this->setting('content_transfer');
		$content = $content ?: 'CHUYEN_TIEN';
		
		if (strpos($content, '{id}') === false)
		{
			$content .= '_{id}';
		}
		
		$content = strtr($content, array(
			'{id}' => $id,
		));
		
		return $content;
	}
	
}