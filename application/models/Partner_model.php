<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Partner_model extends MY_Model {
	
	var $table 	= 'partner';
	
	
	/**
	 * Filter handle
	 */
	function _filter_get_where(array $filter)
	{
		$where = parent::_filter_get_where($filter);
		
		foreach (array('id', 'created') as $p)
		{
			$f = (in_array($p, array())) ? $p.'_id' : $p;
			$f = $this->table.'.'.$f;
			$m = (in_array($p, array('created'))) ? 'range' : '';
			$this->_filter_set_where($filter, $p, $f, $where, $m);
		}
		
		if (isset($filter['key']))
		{
			$v = $this->db->escape_like_str($filter['key']);
			
			$this->db->where("(
				( name LIKE '%{$v}%' ) OR 
				( email LIKE '%{$v}%' ) OR 
				( phone LIKE '%{$v}%' )
			)");
		}
		
		foreach (array('name', 'email', 'phone', 'web') as $p)
		{
			if (isset($filter[$p]))
			{
				$this->db->like($this->table.'.'.$p, $filter[$p]);
			}
		}
		
		return $where;
	}
	
}