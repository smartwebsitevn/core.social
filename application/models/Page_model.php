<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Page_model extends MY_Model {
	
	public $table 	= 'page';
	//public $select = 'id, title, created';
	public $order = array(array('sort_order', 'asc'), array('id', 'desc'));

	public $translate_auto = TRUE;
	public $translate_fields = array('title', 'content','titleweb','url','description','keywords');
	
	
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
		//pr($filter);
		foreach (array('id','type') as $key) {
			if (isset($filter[$key]) && $filter[$key] != -1) {
				//echo '<br>key='.$key.', v='.$filter[$key];
				$this->_filter_parse_where($key, $filter);
			}
		}
		if (isset($filter['status']))
		{
			$v = ($filter['status']) ;//? 'on' : 'off';
			$where[$this->table.'.'.'status'] = config('status_'.$v, 'main');
		}
		if (isset($filter['title']))
		{
			$this->search('page', 'title', $filter['title']);
		}
		//=== Su ly loc theo ngay tao
		//  1: tu ngay  - den ngay
		if (isset($filter['created']) && isset($filter['created_to'])) {
			$where[$this->table . '.created >='] = is_numeric($filter['created']) ? $filter['created'] : get_time_from_date($filter['created']);
			$where[$this->table . '.created <='] = is_numeric($filter['created_to']) ? $filter['created_to'] : get_time_from_date($filter['created_to']) + 24 * 60 * 60;// phai cong them 1 ngay de thoi gian no la cuoi cua ngay hien thoi
		} //2: tu ngay
		elseif (isset($filter['created'])) {
			$where[$this->table . '.created >='] = is_numeric($filter['created']) ? $filter['created'] : get_time_from_date($filter['created']);
		} //3: den ngay
		elseif (isset($filter['created_to'])) {
			$where[$this->table . '.created <='] = is_numeric($filter['created_to']) ? $filter['created_to'] : get_time_from_date($filter['created_to']) + 24 * 60 * 60;// phai cong them 1 ngay de thoi gian no la cuoi cua ngay hien thoi
		}

		// hien thi san pham phia ngoai
		if (isset($filter['show'])) {
			$where[$this->table . '.status'] = '1';
		}
		return $where;
		return $where;
	}
	
	/**
	 * Tao filter tu input
	 */
	function filter_create($fields, &$input = array())
	{
		// Lay gia tri cua filter dau vao
		$input = array();
		foreach ($fields as $f)
		{
			$v = $this->input->get($f);
			$v = security_handle_input($v, in_array($f, array()));
			
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
