<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Table_col_model extends MY_Model {

	var $table 	= 'table_col';
	
	var $join_sql = array(
		'table'			=> 'table_col.table_id = table.id',
		'table_row'		=> 'table_col.id = table_row.table_col_id',
	);
	
	
	/**
	 * Filter handle
	 */
	function _filter_get_where(array $filter)
	{
		$where = parent::_filter_get_where($filter);
		
		// Table table
		$fs = array();
		$fs['table_id'] = 'id';
		$fs['table'] 	= 'name';
		foreach (array('table_id', 'table') as $p)
		{
			$f = (isset($fs[$p])) ? $fs[$p] : $p;
			$f = (in_array($f, array())) ? $f.'_id' : $f;
			$f = 'table.'.$f;
			$m = (in_array($p, array())) ? 'range' : '';
			$this->_filter_set_where($filter, $p, $f, $where, $m);
		}
		
		// Table table_col
		$fs = array();
		$fs['col_id']	= 'id';
		$fs['col'] 		= 'name';
		foreach (array('col_id', 'col') as $p)
		{
			$f = (isset($fs[$p])) ? $fs[$p] : $p;
			$f = (in_array($f, array())) ? $f.'_id' : $f;
			$f = 'table_col.'.$f;
			$m = (in_array($p, array())) ? 'range' : '';
			$this->_filter_set_where($filter, $p, $f, $where, $m);
		}
		
		// Table table_row
		$fs = array();
		$fs['row_id']	= 'id';
		$fs['row'] 		= 'value';
		$fs['_id']		= 'table_row_id';
		foreach (array('row_id', 'row', '_id') as $p)
		{
			$f = (isset($fs[$p])) ? $fs[$p] : $p;
			$f = (in_array($f, array())) ? $f.'_id' : $f;
			$f = 'table_row.'.$f;
			$m = (in_array($p, array())) ? 'range' : '';
			$this->_filter_set_where($filter, $p, $f, $where, $m);
		}
		
		return $where;
	}
	
}