<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Model Core Stats Class
 * 
 * Class model xu ly thong ke
 * 
 * @author		***
 * @version		2014-03-22
 */
class Core_stats_model extends MY_Model {
	
	/**
	 * Key chinh cua table can thong ke
	 * 	Dung mot key: 	'user_id';
	 * 	Dung nhieu key: array('user_id', 'country_id');
	 * @var string || array
	 */
	var $stats_table_key = '';
	
	/**
	 * Cac field can thong ke
	 * @var array
	 */
	var $stats_fields = array();
	
	
/*
 * ------------------------------------------------------
 *  Main handle
 * ------------------------------------------------------
 */
	/**
	 * Ham khoi dong
	 */
	function __construct()
	{
		parent::__construct();
	
		// Gan order mac dinh
		if ( ! $this->order)
		{
			$this->order = array($this->table.'.time', 'asc');
		}
		
		// Gan select mac dinh
		if ( ! $this->select)
		{
			$this->select = $this->table.'.*';
		}
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Lay sum cua field
	 */
	function get_sum($field, $where = array())
	{
		$this->db->select_sum($field);
		$this->db->where($where);
		$this->db->from($this->table);
		
	 	// Tu dong join den cac table duoc khai bao
	 	$this->_join();
	 	
		$row = $this->db->get()->row();
		
		$sum = 0;
		foreach ($row as $f => $v)
		{
			$sum = $v;
		}
		
		return $sum;
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Filter handle
	 */
	function _filter_get_where(array $filter)
	{
		$where = parent::_filter_get_where($filter);
		
		$fs = $this->stats_table_key;
		$fs = ( ! is_array($fs)) ? array($fs) : $fs;
		$fs[] = 'time';
		foreach ($fs as $p)
		{
			$f = (in_array($p, array())) ? $p.'_id' : $p;
			$f = $this->table.'.'.$f;
			$m = (in_array($p, array('time'))) ? 'range' : '';
			$this->_filter_set_where($filter, $p, $f, $where, $m);
		}
		
		return $where;
	}
	
	function filter_get_sum($field, $filter)
	{
		$where = $this->_filter_get_where($filter);
		
		return $this->get_sum($field, $where);
	}
	
	
/*
 * ------------------------------------------------------
 *  Stats handle
 * ------------------------------------------------------
 */
	/**
	 * Lay thong ke
	 * @param mixed 	$table_key		Table key
	 * @param int 		$time			Timestamp
	 * @param mixed 	$info			Thong tin data tra ve
	 * @return array
	 */
	function get($table_key, $time = '', &$info = '')
	{
		// Xu ly input
		$table_key	= $this->_get_table_key($table_key);
		$time		= $this->_get_time($time);
		
		// Lay data
		$info = $this->_get_info($table_key, $time);
		
		return $this->_get_value($info);
	}
	
	/**
	 * Cap nhat thong ke
	 * @param mixed 	$table_key		Table key
	 * @param array 	$stats_update	Gia tri cap nhat them cua cac field stats (VD: array('view' => 1))
	 * @param int 		$time			Timestamp
	 * @param array 	$data			Thong tin muon luu them
	 * @return null
	 */
	function update($table_key, array $stats_update, $time = '', array $data = array())
	{
		// Xu ly input
		$hour = 0;
		$table_key	= $this->_get_table_key($table_key);
		$time 		= $this->_get_time($time, $hour);
		
		// Lay thong ke cu
		$stats_old = $this->get($table_key, $time);
		
		// Tao thong ke moi
		$stats_new = array();
		foreach ($stats_update as $f => $v)
		{
			$stats_new[$f] = $stats_old[$f] + $v;
			
			$h_f = 'hour_'.$f;
			$stats_new[$h_f] = $stats_old[$h_f];
			$stats_new[$h_f][$hour] = (isset($stats_new[$h_f][$hour])) ? $stats_new[$h_f][$hour] + $v : $v;
		}
		
		// Luu vao data
		$this->set($table_key, $stats_new, $time, $data);
	}
	
	/**
	 * Luu thong ke
	 * @param mixed 	$table_key		Table key
	 * @param array 	$stats			Gia tri stats (Giong nhu gia tri tra ve cua fun get())
	 * @param int 		$time			Timestamp
	 * @param array 	$data			Thong tin muon luu them
	 * @return null
	 */
	function set($table_key, array $stats, $time = '', array $data = array())
	{
		// Xu ly input
		$table_key	= $this->_get_table_key($table_key);
		$time		= $this->_get_time($time);
		
		// Tao thong tin luu vao data
		foreach ($stats as $f => $v)
		{
			$data['count_'.$f] = $v;
		}
		
		// Xu ly gia tri
		foreach ($this->stats_fields as $f)
		{
			if (isset($data['count_hour_'.$f]))
			{
				$data['count_hour_'.$f] = serialize($data['count_hour_'.$f]);
			}
		}
		
		// Neu chua ton tai thi them moi
		$info = $this->_get_info($table_key, $time);
		if ( ! $info)
		{
			$data = array_merge($data, $table_key);
			$data['time'] = $time;
			$this->create($data);
		}
		// Neu da ton tai thi update
		else 
		{
			parent::update($info->id, $data);
		}
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Lay danh sach stats
	 * @param mixed 	$table_key		Table key
	 * @param int 		$time			Timestamp
	 * @param array 	$input
	 * @return array
	 */
	function stats_get_list($table_key, $time = '', array $input = array())
	{
		$filter = $this->_stats_get_filter($table_key, $time);
		
		$list = $this->filter_get_list($filter, $input);
		foreach ($list as $i => $row)
		{
			$r = $this->_get_value($row);
			$r['time'] = $row->time;
			
			$list[$i] = $r;
		}
		
		return $list;
	}
	
	/**
	 * Lay tong so stats
	 * @param mixed 	$table_key		Table key
	 * @param int 		$time			Timestamp
	 * @return int
	 */
	function stats_get_total($table_key, $time = '')
	{
		$filter = $this->_stats_get_filter($table_key, $time);
		
		return $this->filter_get_total($filter);
		
	}
	
	/**
	 * Lay tong so count
	 * @param string 	$field			Ten field
	 * @param mixed 	$table_key		Table key
	 * @param int 		$time			Timestamp
	 * @return int || float
	 */
	function stats_get_sum($field, $table_key, $time = '')
	{
		$filter = $this->_stats_get_filter($table_key, $time);
		
		return $this->filter_get_sum('count_'.$field, $filter);
	}
	
	/**
	 * Tao filter xu ly stats
	 * @param mixed 	$table_key		Table key
	 * @param int 		$time			Timestamp
	 * @return array
	 */
	protected function _stats_get_filter($table_key, $time)
	{
		$filter = $this->_get_table_key($table_key);
		if ($time)
		{
			$filter['time'] = $time;
		}
		
		return $filter;
	}
	
	
/*
 * ------------------------------------------------------
 *  Other handle
 * ------------------------------------------------------
 */
	/**
	 * Xu ly gia tri cua table key
	 */
	protected function _get_table_key($table_key)
	{
		if ( ! is_array($table_key))
		{
			$table_key = array($this->stats_table_key => $table_key);
		}
		
		return $table_key;
	}
	
	/**
	 * Lay time theo ngay thang nam
	 */
	protected function _get_time($time, &$hour = 0)
	{
		$time = (int)$time;
		$time = ( ! $time) ? now() : $time;
		
		$info = get_time_info($time);
		$time = mktime(0, 0, 0, $info['m'], $info['d'], $info['y']);
		$hour = $info['h'];
		
		return $time;
	}
	
	/**
	 * Lay thong tin
	 */
	protected function _get_info(array $table_key, $time)
	{
		$where = $table_key;
		$where['time'] = $time;
		
		$info = $this->get_info_rule($where);
		
		return $info;
	}
	
	/**
	 * Lay gia tri stats tu info cua row
	 */
	function _get_value($info)
	{
		$value = array();
		foreach ($this->stats_fields as $f)
		{
			$v = (isset($info->{'count_'.$f})) ? $info->{'count_'.$f} : 0;
			$h_v = (isset($info->{'count_hour_'.$f})) ? @unserialize($info->{'count_hour_'.$f}) : '';
			$h_v = ( ! is_array($h_v)) ? array() : $h_v;
			
			$value[$f] = $v;
			$value['hour_'.$f] = $h_v;
		}
		
		return $value;
	}
	
}