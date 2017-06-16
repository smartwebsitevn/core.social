<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Stats_model extends MY_Model {
	
	var $table = 'stats';
	var $order = array('stats.time', 'asc');
	
	
/*
 * ------------------------------------------------------
 *  Main handle
 * ------------------------------------------------------
 */
	/**
	 * Lay sum cua field
	 */
	function get_sum($field, $where = array())
	{
		$this->db->select_sum($field);
		$this->db->where($where);
		$this->db->from($this->table);
		
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
		
		foreach (array('table', 'table_id', 'table_field', 'time') as $p)
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
	 * Lay stats
	 */
	function get($table, $time = '')
	{
		// Xu ly input
		$table 	= $this->_get_table($table);
		$time 	= $this->_get_time($time);
		
		// Lay data
		$info = $this->_get_info($table[0], $table[1], $table[2], $time);
		
		return $this->_get_value($info);
	}
	
	/**
	 * Cap nhat stats
	 */
	function update($table, $num, $time = '')
	{
		// Xu ly input
		$hour 	= 0;
		$table 	= $this->_get_table($table);
		$time 	= $this->_get_time($time, $hour);
		$num	= (float)$num;
		
		// Lay va cap nhat stats
		$stats = $this->get($table, $time);
		$stats['count'] = (float)$stats['count'] + $num;
		$stats['count_hour'][$hour] = (isset($stats['count_hour'][$hour])) ? (float)$stats['count_hour'][$hour] + $num : $num;
		
		// Luu vao data
		$this->set($table, $stats, $time);
	}
	
	/**
	 * Luu stats
	 */
	function set($table, array $stats, $time = '')
	{
		// Xu ly input
		$table 	= $this->_get_table($table);
		$time 	= $this->_get_time($time);
		
		// Tao thong tin luu vao data
		$data = array();
		if (isset($stats['count']))
		{
			$data['count'] = $stats['count'];
		}
		if (isset($stats['count_hour']))
		{
			$data['count_hour'] = serialize($stats['count_hour']);
		}
		
		// Neu chua ton tai thi them moi
		$info = $this->_get_info($table[0], $table[1], $table[2], $time);
		if ( ! $info)
		{
			$data['table'] 			= $table[0];
			$data['table_id'] 		= $table[1];
			$data['table_field']	= $table[2];
			$data['time'] 			= $time;
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
	 */
	function stats_get_list($table, $time = '', $input = array())
	{
		$filter = $this->_stats_get_filter($table, $time);
		
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
	 */
	function stats_get_total($table, $time = '')
	{
		$filter = $this->_stats_get_filter($table, $time);
		
		return $this->filter_get_total($filter);
		
	}
	
	/**
	 * Lay tong so count
	 */
	function stats_get_sum($table, $time = '')
	{
		$filter = $this->_stats_get_filter($table, $time);
		
		return (float)$this->filter_get_sum('count', $filter);
	}
	
	/**
	 * Tao filter xu ly stats
	 */
	protected function _stats_get_filter($table, $time)
	{
		// Xu ly input
		$table = $this->_get_table($table);
		
		// Tao filter
		$filter = array();
		$filter['table'] 		= $table[0];
		$filter['table_id'] 	= $table[1];
		$filter['table_field']	= $table[2];
		if ($time)
		{
			$filter['time'] = $time;
		}
		
		return $filter;
	}
	
	
/*
 * ------------------------------------------------------
 *  Private handle
 * ------------------------------------------------------
 */
	/**
	 * Xu ly gia tri cua table
	 */
	protected function _get_table($table)
	{
		$table = ( ! is_array($table)) ? array($table) : $table;
		$table = set_default_value($table, array(0, 1, 2), '');
		
		return $table;
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
	protected function _get_info($table, $table_id, $table_field, $time)
	{
		$where = array();
		$where['table'] 		= $table;
		$where['table_id'] 		= $table_id;
		$where['table_field']	= $table_field;
		$where['time'] 			= $time;
		$info = $this->get_info_rule($where);
		
		return $info;
	}
	
	/**
	 * Lay gia tri cua stats
	 */
	function _get_value($info)
	{
		$value = array();
		$value['count'] = (isset($info->count)) ? (float)$info->count : 0;
		
		$value['count_hour'] = (isset($info->count_hour)) ? @unserialize($info->count_hour) : '';
		$value['count_hour'] = ( ! is_array($value['count_hour'])) ? array() : $value['count_hour'];
		
		return $value;
	}
	
}