<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	/**
	 * Lay thong tin cua product
	 */
	function product_get_info($product_id, $field = '')
	{
		return mod('product')->get_info($product_id, $field);
	}
	
	/**
	 * Them cac thong tin phu vao thong tin cua product
	 */
	function product_add_info($product)
	{
		return mod('product')->add_info($product);
	}
	
	/**
	 * Lay gia cua product theo user_group
	 */
	function product_get_price($product, $user_group_id = 0)
	{
		return mod('product')->price($product, $user_group_id);
	}
	
	/**
	 * Lay discount cua product theo user_group
	 */
	function product_get_discount($product_id, $user_group_id = 0)
	{
		return mod('product')->discount($product_id, $user_group_id);
	}
	
	/**
	 * Lay so luong the hien co
	 */
	function product_get_available($product)
	{
		return mod('product')->available($product);
	}
	