<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tool_chat_model extends MY_Model {
	
	public $table 	= 'tool_chat';
	public $order	= array('created', 'desc');


	function get_list_home($limit=50)
	{
		$input['limit']=array(0,$limit);
		return $this->get_list($input);
	}
	/**
	 * Filter handle
	 */
	function _filter_get_where(array $filter)
	{
		$where = parent::_filter_get_where($filter);

		foreach (array('id','created') as $p)
		{
			$f = (in_array($p, array())) ? $p.'_id' : $p;
			$f = $this->table.'.'.$f;
			$m = (in_array($p, array('created'))) ? 'range' : '';
			$this->_filter_set_where($filter, $p, $f, $where, $m);
		}


		//pr($where);
		foreach (array('content') as $p)
		{
			if (isset($filter[$p]))
			{
				$this->db->like($this->table.'.'.$p, $filter[$p]);
			}
		}
		return $where;
	}
	
}