<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class News_cat_model extends MY_Model {
	
	public $table 	= 'news_cat';
	public $order	= array('sort_order', 'asc');
	public $translate_auto = TRUE;
	public $translate_fields = array('name','titleweb','url','description','keywords');
	
	
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
	
}