<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Php_query_library {
	
	function __construct()
	{
		require_once APPPATH.'libraries/phpQuery/phpQuery'.EXT;
	}
	
	
	/**
	 * Load html
	 */
	function load($html, $contentType = null)
	{
		libxml_use_internal_errors(true);
		$doc = phpQuery::newDocument($html, $contentType);
		libxml_clear_errors();
		
		return $doc;
	}
	
	/**
	 * Lay ten va gia tri cua cac bien trong html
	 */
	function get_params($html)
	{
		$params = array();
		$doc = phpQuery::newDocument($html);
		
		// Input
		foreach ($doc->find('input[name]') as $input)
		{
			// Lay attr
			$input		= pq($input);
			$name 		= (string)$input->attr('name');
			$value 		= (string)$input->val();
			$type 		= strtolower($input->attr('type'));
			$checked 	= $input->is(':checked');
			$is_array 	= (right($name, 2) == '[]') ? TRUE : FALSE;
			$name_array	= ($is_array) ? left($name, strlen($name)-2) : $name;
			
			// Xu ly voi cac loai input
			switch ($type)
			{
				case 'radio':
				{
					if ($checked)
					{
						$params[$name] = $value;
					}
					break;
				}
				
				case 'text':
				case 'password':
				case 'checkbox':
				case 'hidden':
				case 'submit':
				{
					if ($type == 'checkbox' && !$checked)
					{
						break;
					}
					
					if ($is_array)
					{
						$params[$name_array][] = $value;
					}
					else 
					{
						$params[$name] = $value;
					}
					break;
				}
			}	
		}
		
		// Textarea
		foreach ($doc->find('textarea[name]') as $textarea)
		{
			// Lay attr
			$textarea	= pq($textarea);
			$name 		= (string)$textarea->attr('name');
			$value 		= (string)$textarea->val();
			$is_array 	= (right($name, 2) == '[]') ? TRUE : FALSE;
			$name_array	= ($is_array) ? left($name, strlen($name)-2) : $name;
			
			if ($is_array)
			{
				$params[$name_array][] = $value;
			}
			else 
			{
				$params[$name] = $value;
			}
		}
		
		// Select
		foreach ($doc->find('select[name]') as $select)
		{
			// Lay attr
			$select	= pq($select);
			$name 	= (string)$select->attr('name');
			$value 	= array();
			$multi	= (strtolower($select->attr('multiple')) == 'multiple') ? TRUE : FALSE;
			$is_array 	= (right($name, 2) == '[]') ? TRUE : FALSE;
			$name_array	= ($is_array) ? left($name, strlen($name)-2) : $name;
			
			// Lay gia tri cua cac option selected
			foreach ($select->find('option:selected') as $option)
			{
				$option = pq($option);
				$val 	= $option->val();
				$value[] = ($val === NULL) ? $option->text() : $val;
			}
			
			// Neu khong phai che do multi
			if (!$multi)
			{
				// Neu khong get duoc value thi lay value cua option dau tien
				if (!count($value))
				{
					$option = $select->find('option:first');
					$val 	= $option->val();
					$value[] = ($val === NULL) ? $option->text() : $val;
				}
				
				// Lay gia tri cuoi cung
				$value = (string)end($value);
			}
			
			// Gan gia tri
			if ($is_array)
			{
				if ($multi)
				{
					$params[$name_array] = $value;
				}
				else 
				{
					$params[$name_array][] = $value;
				}
			}
			else 
			{
				$params[$name] = $value;
			}
		}
		
		return $params;
	}
	
	/**
	 * Gan gia tri cho cac bien (su dung regex khop ten bien)
	 */
	function set_value_params($params, $param_values)
	{
		foreach ($params as $param => $value)
		{
			foreach ($param_values as $p => $v)
			{
				if (preg_match('#'.$p.'#i', $param))
				{
					$params[$param] = $v;
				}
			}
		}
		
		return $params;
	}
	
}

?>