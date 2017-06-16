<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class News_model extends MY_Model {
	
	public $table 	= 'news';
	//public $select = 'id, title, intro, image_name, created, count_view, feature';
	public $order	= array( array('sort_order', 'asc'),array('id', 'desc'));
	public $translate_auto = TRUE;
	public $translate_fields = array('title', 'intro', 'content','titleweb','url','description','keywords');
	
	public $_options = array('feature');
	
	
/*
 * ------------------------------------------------------
 *  Main handle
 * ------------------------------------------------------
 */
	/**
	 * Filter handle
	 */
	function _filter_get_where(array $filter)
	{
		$where = parent::_filter_get_where($filter);
		
		foreach (array('id', 'created', 'cat_news') as $p)
		{
			$f = (in_array($p, array('cat_news'))) ? $p.'_id' : $p;
			$f = $this->table.'.'.$f;
			$m = (in_array($p, array('created'))) ? 'range' : '';
			$this->_filter_set_where($filter, $p, $f, $where, $m);
		}
		/*if (isset($filter['tag']) && count($filter['tag']) >= 2)
		{
			$where["`id` in (select `table_id` from `tag_value` where `type` = '".$filter['tag'][1]."' and `tag_id` = ".$filter['tag'][0].")"] = null;
		}*/
		if (isset($filter['title']))
		{
			$this->search('news', 'title', $filter['title']);
		}
		if (isset($filter['image']))
		{
			$where[$this->table . '.image_id >' ] = '0';
		}
		if (isset($filter['status']))
		{
			$v = ($filter['status']) ;//? 'on' : 'off';
			$where[$this->table.'.'.'status'] = config('status_'.$v, 'main');
		}
		//== Thuoc tinh bool dang ngay
		foreach (array('feature') as $f) {
			if (isset($filter[$f])) {
				$v = ($filter[$f]);//? 'on' : 'off';
				if (is_numeric($v))
					$v = $v ? 'on' : 'off';
				if ($v == 'off' || $v == 'no' )
					$where[$this->table . '.' . $f] = '0';
				else
					$where[$this->table . '.' . $f.' >'] = 0;
			}
		}
		foreach ($this->_options as $p)
		{
			if ( ! isset($filter[$p])) continue;
			
			if ($filter[$p])
			{
				$where["news.{$p} >"] = 0;
			}
			else 
			{
				$where["news.{$p}"] = 0;
			}
		}
		
		return $where;
	}
	
	/**
	 * Tao filter tu input
	 */
	function filter_create($fields, &$input = array())
	{
		// Lay config
		$options = $this->_options;
		
		// Lay gia tri cua filter dau vao
		$input = array();
		foreach ($fields as $f)
		{
			$v = $this->input->get($f);
			$v = security_handle_input($v, in_array($f, array()));
			
			if (
				$f == 'option' && ! in_array($v, $options)
			)
			{
				$v = '';
			}
			
			$input[$f] = $v;
		}
		
		if ( ! empty($input['id']))
		{
			foreach ($input as $f => $v)
			{
				$input[$f] = ($f != 'id') ? '' : $v;
			}
		}
		
		// Tao bien filter
		$filter = array();
		$query 	= url_build_query($input, TRUE);
		foreach ($query as $f => $v)
		{
			switch ($f)
			{
				case 'option':
				{
					$f = $v;
					$v = TRUE;
					break;
				}
				case 'created':
				{
					$created_to = $input['created_to'];
					$v = (strlen($created_to)) ? array($v, $created_to) : $v;
					$v = get_time_between($v);
					$v = ( ! $v) ? NULL : $v;
					break;
				}
			}
			
			if ($v === NULL) continue;
			
			$filter[$f] = $v;
		}
		
		return $filter;
	}
	
	/**
	 * Tim kiem du lieu
	 */
	function _search($field, $key)
	{
		switch ($field)
		{
			case 'title':
			{
				$this->db->like($this->table.'.title', $key);
				break;
			}
		}
	}
	
}