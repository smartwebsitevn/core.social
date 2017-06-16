<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Seo_url_model extends MY_Model
{
	public $table 	= 'seo_url';
	
	
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
			$m = (in_array($p, array('created'))) ? 'range' : '';
			$this->_filter_set_where($filter, $p, $f, $where, $m);
		}
		
		if (isset($filter['key']))
		{
			$v = $this->db->escape_like_str($filter['key']);
			
			$this->db->where("(
				( url_original LIKE '%{$v}%' ) OR 
				( url_seo LIKE '%{$v}%' ) OR 
				( url_base LIKE '%{$v}%' )
			)");
		}
		
		foreach (array('url_original', 'url_seo', 'url_base') as $p)
		{
			if (isset($filter[$p]))
			{
				$this->db->like($this->table.'.'.$p, $filter[$p]);
			}
		}
		
		return $where;
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Lay danh sach luu trong bien static
	 */
	function get_list_static()
	{
		static $list;
		
		if (is_null($list))
		{
			$input = array();
			$input['select'] = 'url_original, url_seo, url_base';
			
			$list = $this->get_list($input);
		}
		
		return $list;
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Lay danh sach route (Kieu du lieu giong nhu file routes trong config)
	 */
	function get_list_route()
	{
		$data = $this->get_list_static();
		
		$list = array();
		foreach ($data as $row)
		{
			$list[$row->url_seo] = $row->url_base;
		}
		
		return $list;
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Lay url_seo tu url_original
	 * @param string $url_original	Url goc
	 */
	function get_url_seo_from_original($url_original)
	{
		$data = $this->get_list_static();
		foreach ($data as $row)
		{
			if ($row->url_original == $url_original)
			{
				return $row->url_seo;
			}
		}
		
		return FALSE;
	}
	
}