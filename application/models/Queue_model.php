<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Queue_model extends MY_Model
{
	public $table = 'queue';
	
	public $timestamps = true;
	
	
	/**
	 * Filter handle
	 */
	public function _filter_get_where(array $filter)
	{
		$where = parent::_filter_get_where($filter);
	
		foreach (array('id', 'key', 'status', 'created') as $p)
		{
			$f = (in_array($p, array())) ? $p.'_id' : $p;
			$f = $this->table.'.'.$f;
			$m = (in_array($p, array('created'))) ? 'range' : '';
			$this->_filter_set_where($filter, $p, $f, $where, $m);
		}
		
		return $where;
	}
	
	/**
	 * Gan trang thai handling
	 * 
	 * @param int $id
	 */
	public function set_handling($id)
	{
		$this->update($id, [
			'status' 	=> 'handling',
			'handled' 	=> now(),
		]);
	}
	
}