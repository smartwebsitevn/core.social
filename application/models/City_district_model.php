<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class City_district_model extends MY_Model {
	
	var $table 	= 'city_district';
	var $order 	= array('name', 'asc');
	
	
/*
 * ------------------------------------------------------
 *  Main handle
 * ------------------------------------------------------
 */
	function get_info($id)
	{
		$list = $this->cache_get();
		foreach ($list as $it)
		{
			if ($id == $it->id)
			{
				return $it;
			}
		}
		return null;
	}
	/**
	 * Lay danh sach theo city
	 */
	function get($city_id = 0)
	{
		$list = $this->cache_get();
		
		if ($city_id)
		{
			$result = array();
			foreach ($list as $row)
			{
				if ($row->city_id == $city_id)
				{
					$result[] = $row;
				}
			}
			
			return $result;
		}
		
		return $list;
	}

	
/*
 * ------------------------------------------------------
 *  Cache handle
 * ------------------------------------------------------
 */
	/**
	 * Update lai cache
	 */
	function cache_update()
	{
		// Lay danh sach trong data
		$list = $this->get_list();
		
		// Luu vao cache
		$this->load->driver('cache');
		$this->cache->file->save('m_district', $list, config('cache_expire_long', 'main'));
		
		return $list;
	}
	
	/**
	 * Lay danh sach trong cache
	 */
	function cache_get()
	{	
		// Tai file thanh phan
		$this->load->driver('cache');
		
		// Lay danh sach trong cache
		$list = $this->cache->file->get('m_district');
		
		// Neu khong ton tai thi cap nhat cache tu data va get lai
		if ($list === FALSE)
		{
			$list = $this->cache_update();
		}
		
		return $list;
	}
	
	
/*
 * ------------------------------------------------------
 *  Protected Function
 * ------------------------------------------------------
 */
	/**
	 * Ham duoc goi khi du lieu thay doi
	 */
	function _event_change($act, $params)
	{
		// Cap nhat lai cache
		$this->cache_update();
	}
	
}
?>