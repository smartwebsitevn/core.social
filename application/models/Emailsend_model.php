<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class emailsend_model extends MY_Model {
	
	var $table 	= 'emailsend';
	var $order 	= array('id', 'desc');

	/**
	 * Tao filter tu input
	 */
	function filter_create($fields, &$input = array())
	{
		// Lay config
		$options = $this->_options;

		// Lay gia tri cua filter dau vao
		$input = array();
		foreach ($fields as $f)
		{
			$v = $this->input->get($f);
			$v = security_handle_input($v, in_array($f, array()));

			if (
					$f == 'option' && ! in_array($v, $options)
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
				case 'option':
				{
					$f = $v;
					$v = TRUE;
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

			if ($v === NULL) continue;

			$filter[$f] = $v;
		}

		return $filter;
	}
}