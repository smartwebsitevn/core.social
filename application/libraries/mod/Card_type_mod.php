<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Card_type_mod extends MY_Mod
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
		
		foreach (array('fee') as $p)
		{
			if (isset($row->$p))
			{
				$row->$p = (float) $row->$p;
			}
		}
		
		if (isset($row->fee_user_group) && ! is_array($row->fee_user_group))
		{
			$val = json_decode($row->fee_user_group, true);
			
			$row->fee_user_group = is_array($val) ? $val : [];
		}
		
		if (isset($row->image_name) && ! isset($row->image))
		{
			t('load')->helper('file');
			$row->image = file_get_image_from_name($row->image_name, public_url('img/no_image.png'));
		}
		
		if (isset($row->status))
		{
			$row->_status = $row->status ? 'on' : 'off';
		}
		
		if (isset($row->fee) && isset($row->fee_user_group))
		{
			$row->user_fee = $this->get_fee($row);
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

			if (
				($f == 'status' && ! in_array($v, array('off', 'on')))
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
					$v = ($v == 'on') ? 1 : 0;
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
	 * Lay gia tri cua fee
	 * 
	 * @param object 	$card_type
	 * @param null|int 	$user_group_id
	 * @return float
	 */
	public function get_fee($card_type, $user_group_id = null)
	{
		$fee_user_group = is_array($card_type->fee_user_group)
			? $card_type->fee_user_group
			: json_decode($card_type->fee_user_group, true);
		

		$user_group_id = $user_group_id ?: mod('user_group')->current()->id;
		
		$fee = array_get($fee_user_group, (int) $user_group_id);
		
		$fee = $fee ?: $card_type->fee;
		
		return (float) $fee;
	}
	
}