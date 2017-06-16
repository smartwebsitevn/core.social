<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Chuyển số thành dạng chuỗi tiền tệ
 * 
 */
function dinhdangtien($n)
{
	$temp = (string)$n;
	$r = "";
	while(strlen($temp) > 3)
	{
		$r = substr($temp, strlen($temp)-3) . "," . $r;
		$temp = substr($temp, 0, strlen($temp)-3);
	}
	$r = $temp . "," . $r;
	return substr($r, 0, strlen($r)-1);
}


/**
 * Get rows form object by condition
 * 
 * @param  String 		$key   	Name of the field
 * @param  Undefined 	$value 	Value to compare
 * @param  Objects 		$rows  	Object to be searched
 * @return Array        
 * 
 */
function objectExtract( $where, $rows, $one = false )
{
	
	$output = array();
	foreach ($rows as $row) 
	{
		$flag = true;
		foreach( $where as $k => $v )
		{
			if( is_array($v) )
			{
				if(! in_array( $row->{$k}, $v ) )
					$flag = false;
			}
			else
			{
				if( $row->{$k} != $v )
					$flag = false;
			}
		}
		
		if( $flag )
			$output[] = $row;
	}

	if( count($output) )
		return $one ? $output[0] : $output;
	else 
		return $output;

	return false;
	
}

/**
 * Get array of value in a column
 * from multiple demension arrays
 * 
 * @param  [type] $rows   [description]
 * @param  [type] $column [description]
 * @return [type]         [description]
 * 
 */
function array_gets( $rows, $column )
{
	$rs = array();

	if( ! empty($rows) )
		foreach ($rows as $row) 
		{
			if( is_array($column) )
			{
				if( empty( $rs[$row->{$column[0]}] ) )
					$rs[$row->{$column[0]}] = $row->{$column[1]};
			}
			else
			{
				if( !in_array( $row->{$column}, $rs ) )
					$rs[] = $row->{$column};
			}
		}
		
	return $rs;
}

/**
 * Escape data input
 * 
 * @param  [type] $str [description]
 * @return [type]      [description]
 * 
 */
function escape($str)
{
	$CI =& get_instance();
	$CI->load->database();
	return trim($CI->db->escape_str($str));
}

/**
 * Check if slug is contain special characters
 * 
 * @param  [type]  $slug [description]
 * @return boolean       [description]
 * 
 */
function is_slug( $slug )
{
    if( preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $slug) ){
        return true;
    }
    return false;
}