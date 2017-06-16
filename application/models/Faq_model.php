<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Faq_model extends MY_Model {
	
	var $table 	= 'faq';
	var $order 	= array('sort_order', 'asc');
	public $translate_auto = TRUE;
	public $translate_fields = array('question', 'answer');
	
	
	/**
	 * Filter handle
	 */
	function _filter_get_where(array $filter)
	{
		$where = parent::_filter_get_where($filter);
		
		foreach (array('id', 'cat') as $p)
		{
			$f = (in_array($p, array('cat'))) ? $p.'_id' : $p;
			$f = 'faq.'.$f;
			$m = (in_array($p, array())) ? 'range' : '';
			$this->_filter_set_where($filter, $p, $f, $where, $m);
		}
		
		if (isset($filter['question']))
		{
			$this->search('faq', 'question', $filter['question']);
		}
		
		return $where;
	}
	
	/**
	 * Tim kiem du lieu
	 */
	function _search($field, $key)
	{
		switch ($field)
		{
			case 'question':
			{
				$this->db->like('faq.question', $key);
				break;
			}
		}
	}
	
}