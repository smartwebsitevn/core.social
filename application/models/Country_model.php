<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class Country_model extends MY_Model {

	public $table 	= 'country';
	//public $order 	= array('name', 'asc');
	//var $order = array(array('sort_order', 'asc'),array('name', 'asc'));
	var $order = array(array('sort_order', 'asc'),array('feature', 'desc'),array('name', 'asc'));

	/**
	 * Filter handle
	 */
	function _filter_get_where(array $filter)
	{
		$where = parent::_filter_get_where($filter);
		foreach (['status'] as $key) {
			if (isset($filter[$key]) && $filter[$key] != -1) {
				//echo '<br>key='.$key.', v='.$filter[$key];
				$this->_filter_parse_where($key, $filter);
			}
		}
		if (isset($filter['id']))
		{
			$where[$this->table.'.'.'id'] = $filter['id'];
		}

		if (isset($filter['group_id']) && $filter['group_id']>0)
		{
			$where[$this->table.'.'.'group_id'] = $filter['group_id'];
		}



		if (isset($filter['name']))
		{
			$this->search('country', 'name', $filter['name']);
		}

		if (isset($filter['code']))
		{
			$this->search('country', 'code', $filter['code']);
		}

		if (isset($filter['feature']))
		{
			$where[$this->table.'.feature>'] = 0;
		}
		if (isset($filter['show']))
		{
				$where[$this->table.'.'.'status'] = 1;
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

			case 'code':
			{
				$this->db->like($this->table.'.code', $key);
				break;
			}

		}

	}


	function get_grouped(){
		$locations = $this->cache_get();
		$location_groups = model('country_group')->get_list();

		foreach ($location_groups as $g)
		{
			$g->countries = array();
			foreach ($locations as $i => $l)
			{
				if ($l->group_id == $g->id)
				{
					$g->countries[] = $l;
					unset($locations[$i]);
				}
			}
		}
		return $location_groups;
	}


	function get($id)
	{
		$list =$this->cache_get();
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
	 * Lay danh sach kem theo muc con neu co
	 */
	function get_all()
	{
		$cats = $this->cache_get();
		return $cats;
	}
	/**
	 * Cache Handle
	 */

	function cache_get(){
		t()->load->driver('cache');
		$cache = 'country';
		$cache = t()->cache->file->get($cache);
		if(!$cache){
			// Lay danh sach
			$cache = $this->cache_update();
		}
		return $cache;
	}
	function cache_update(){
		$list = $this->get_list();
		$this->cache_set($list);
		return $list;
	}
	function cache_set($data){
		t()->load->driver('cache');
		$cache = 'country';
		t()->cache->file->save($cache, $data, config('cache_expire_long', 'main'));
		return $data;
	}

	function cache_del(){
		$path = 'application/cache/country';
		delete_files($path,true);
	}
	/**
	 * Ham duoc goi khi du lieu thay doi
	 */
	function _event_change($act, $params)
	{
		// Cap nhat lai cache
		$this->cache_update();
	}
	

}