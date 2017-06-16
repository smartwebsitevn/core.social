<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Seo_word_model extends MY_Model
{
	public $table 	= 'seo_word';
	public $select = 'id, url, title';
	
	public $_param_old_value = '{value}';
	
	
	/**
	 * Filter handle
	 */
	public function _filter_get_where(array $filter)
	{
		$where = parent::_filter_get_where($filter);
		
		foreach (array('id') as $p)
		{
			$f = (in_array($p, array())) ? $p.'_id' : $p;
			$f = $this->table.'.'.$f;
			$m = (in_array($p, array())) ? 'range' : '';
			$this->_filter_set_where($filter, $p, $f, $where, $m);
		}
		
		if (isset($filter['key']))
		{
			$v = $this->db->escape_like_str($filter['key']);
			
			$this->db->where("(
				( url LIKE '%{$v}%' ) OR 
				( title LIKE '%{$v}%' )
			)");
		}
		
		foreach (array('url', 'title') as $p)
		{
			if (isset($filter[$p]))
			{
				$this->db->like($this->table.'.'.$p, $filter[$p]);
			}
		}
		
		return $where;
	}
	
}