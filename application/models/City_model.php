<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class City_model extends MY_Model {
	
	var $table 	= 'city';
	//var $order = array('name', 'asc');
	var $order = array(array('country_id', 'asc'),array('sort_order', 'asc'),array('feature', 'desc'),array('name', 'asc'));


	/**
	 * Filter handle
	 */
	function _filter_get_where(array $filter)
	{
		$where = parent::_filter_get_where($filter);
		if (isset($filter['country_id']))
		{
			$where[$this->table.'.'.'country_id'] = $filter['country_id'];
		}

		if (isset($filter['feature']))
		{
			$where[$this->table.'.feature>'] = 0;
		}

		if (isset($filter['status']))
		{
			if( $filter['status'] != -1 )
			$where[$this->table.'.'.'status'] = $filter['status'];
		}

		if (isset($filter['name']))
		{
			$this->search('city', 'name', $filter['name']);
		}
		
		return $where;
	}

	/**
	 * Tao filter tu input
	 */
	function filter_create($fields, &$input = array())
	{
		/* Lay gia tri cua filter dau vao */
		$input = array();
		foreach ($fields as $f)
		{
			$v = $this->input->get($f);
			$v = security_handle_input($v, in_array($f, array()));
			
			$input[$f] = $v;
		}
		
		/* Tao bien filter */
		$filter = array();
		$query 	= url_build_query($input, TRUE);
		foreach ($query as $f => $v)
		{
			if ($v === NULL) continue;
			
			$filter[$f] = $v;
		}
		
		return $filter;
	}

	/**
	 * Tim kiem du lieu
	 */
	function _search($field, $key)
	{
		switch ($field)
		{
			case 'name':
			{
				$this->db->like($this->table.'.name', $key);
				break;
			}
		}
	}


	function get($id)
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
	 * Lay danh sach thanh pho co kem theo quan huyen
	 */
	function get_list_full()
	{
		$this->load->model('city_district_model');
		$districts = $this->city_district_model->cache_get();

		$cities = $this->cache_get();
		foreach ($cities as $city)
		{
			$city->districts = array();
			foreach ($districts as $district)
			{
				if ($district->city_id == $city->id)
				{
					$city->districts[] = $district;
				}
			}
		}

		return $cities;
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
		$input = array();
		$input['where']['feature >'] = 0;
		$input['order'] = array('feature', 'desc');
		$list_feature = $this->get_list($input);

		$input = array();
		$input['where']['feature'] = 0;
		$input['order'] = array('name', 'asc');
		$list_normal = $this->get_list($input);

		// Xu ly list
		$list = array_merge($list_feature, $list_normal);
		foreach ($list as $i => $row)
		{
			$row->name_uri = url_title(convert_vi_to_en($row->name));
		}

		// Luu vao cache
		$this->load->driver('cache');
		$this->cache->file->save('m_city', $list, config('cache_expire_long', 'main'));

		return $list;
	}

	/**
	 * Lay danh sach trong cache
	 */
	function cache_get($region = NULL)
	{
		// Tai file thanh phan
		$this->load->driver('cache');

		// Lay danh sach trong cache
		$list = $this->cache->file->get('m_city');

		// Neu khong ton tai thi cap nhat cache tu data va get lai
		if ($list === FALSE)
		{
			$list = $this->cache_update();
		}

		// Loc theo region
		if ($region !== NULL)
		{
			$result = array();
			foreach ($list as $row)
			{
				if ($row->region == $region)
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