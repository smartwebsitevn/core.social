<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Log_model extends MY_Model
{
	public $table = 'log';

	public $relations = array(
		'user' => array('one', 'table_id', 'id'),
	);
	
	
	/**
	 * Filter handle
	 */
	function _filter_get_where(array $filter)
	{
		$where = parent::_filter_get_where($filter);
		
		foreach (array('id', 'table', 'table_id', 'action', 'user', 'admin', 'ip', 'created') as $p)
		{
			$f = (in_array($p, array())) ? $p.'_id' : $p;
			$f = $this->table.'.'.$f;
			$m = (in_array($p, array('created'))) ? 'range' : '';
			$this->_filter_set_where($filter, $p, $f, $where, $m);
		}
		
		return $where;
	}
	
	/**
	 * Luu log
	 */
	function add($table, $table_id, $action, array $data = array())
	{
		$data['table'] 		= $table;
		$data['table_id'] 	= $table_id;
		$data['action'] 	= $action;
		$data['ip'] 		= array_get($data, 'ip', t('input')->ip_address());
		$data['user'] 		= array_get($data, 'user', 0);
		$data['admin'] 		= array_get($data, 'admin',0);
		$data['created'] 	= now();
		
		$this->create($data);
	}
	
	/**
	 * Lay log gan day nhat
	 */
	function get_last($table, $table_id, $action = '', $limit = 0)
	{
		$filter = array();
		$filter['table'] 	= $table;
		$filter['table_id'] = $table_id;
		if ($action != '')
		{
			$filter['action'] = $action;
		}
		
		$input = array();
		$input['order'] 	= array('log.id', 'desc');
		$input['limit'] 	= array($limit, 1);
		
		$list 	= $this->filter_get_list($filter, $input);
		$row 	= (isset($list[0])) ? $list[0] : FALSE;
		
		return $row;
	}
	
	/**
	 * Don dep du lieu
	 */
	function cleanup($table, $table_id = '', $action = '', $timeout = 0)
	{
		$timeout = ( ! $timeout) ? 30*24*60*60 : $timeout;
		
		$where = array();
		$where['table'] = $table;
		if ($table_id != '')
		{
			$where['table_id'] = $table_id;
		}
		if ($action != '')
		{
			$where['action'] = $action;
		}
		$where['created <'] = now() - $timeout;
		
		$this->del_rule($where);
	}
	
}