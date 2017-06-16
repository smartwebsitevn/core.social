<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Range_model extends MY_Model {

	public $table 	= 'range';
	public $order	= array('sort_order', 'asc');
	//public $translate_auto = TRUE;
	//public $translate_fields = array('name');


	/**
	 * Filter handle
	 */
	function _filter_get_where(array $filter)
	{
		$where = parent::_filter_get_where($filter);

		foreach (array('id') as $p)
		{
			$f = (in_array($p, array())) ? $p.'_id' : $p;
			$f = $this->table.'.'.$f;
			$m = (in_array($p, array('created'))) ? 'range' : '';
			$this->_filter_set_where($filter, $p, $f, $where, $m);
		}

		if (isset($filter['ids']))
		{
			$this->db->where_in($this->table.'.'.'id',$filter['ids']);
			//$where[$this->table.'.'.'id'] = explode(',',$filter['ids']);
		}
		if (isset($filter['status']))
		{
			$v = ($filter['status']) ;//? 'on' : 'off';
			$where[$this->table.'.'.'status'] = config('status_'.$v, 'main');
		}
		//pr($where);
		foreach (array('name') as $p)
		{
			if (isset($filter[$p]))
			{
				$this->db->like($this->table.'.'.$p, $filter[$p]);
			}
		}
		return $where;
	}


	/**
	 * Lay danh sach the loai da qua xu ly
	 */
	function get($id,$type)
	{
		$list =$this->get_type($type);
		foreach ($list as $row)
		{
			if ($row->id == $id)
			{
				return $row;
			}
		}
		return null;
	}
	function get_type($type/* ,$lang_id*/ )
	{
		static $list = array();
		if(!isset($list[$type]))
			$list[$type] = $this->cache_get($type);
		return $list[$type];
	}




	function cache_get($type, $lang_id = 0){
		t()->load->driver('cache');
		$cache = 'range/'.$type;
		$cache = t()->cache->file->get($cache);
		if(!$cache){
			$cache = $this->cache_update($type);
		}
		return $cache;
	}

	function cache_update($type){
		$input = array();
		$input['where']['type'] = $type;
		$list = $this->get_list($input);
		$this->cache_set($type,$list);
		return $list;
	}
	function cache_set($type,$data, $lang_id = 0){
		t()->load->driver('cache');
		$cache = 'range/'.$type;
		//echo "<br>==$lang_cache:".$lang_cache;
		t()->cache->file->save($cache, $data, config('cache_expire_long', 'main'));

		return $data;
	}
	function cache_del($type, $lang_id = 0){
		$path = 'application/cache/range/'.$type;
		delete_files($path,true);
		//@rmdir($path);
	}
	/**
	 * Ham duoc goi khi du lieu thay doi
	 */
	function _event_change($act, $params)
	{
		$where 	= $params[0];
		$id = $where[$this->key];
		$info =$this->get_info($id);
		// Cap nhat lai cache
		$this->cache_update($info->type);
	}

}