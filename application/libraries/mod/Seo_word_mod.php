<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Seo_word_mod extends MY_Mod
{
	/**
	 * Them cac thong tin phu
	 *
	 * @param object $row
	 * @return object
	 */
	public function add_info($row)
	{
		$row = parent::add_info($row);
		
		if (isset($row->url))
		{
			$row->url = handle_content($row->url, 'output');
			$row->uri = url_get_uri($row->url);
		}
		
		return $row;
	}
	
	/**
	 * Xu ly thong tin trang
	 * 
	 * @param array $page_info
	 * @return array
	 */
	public function handle_page_info(array $page_info)
	{
		// Lay seo_word phu hop voi url hien tai
		$seo_word = $this->get_match_url(current_url(TRUE));
		if ( ! $seo_word)
		{
			return $page_info;
		}
		
		// Gan cac gia tri
		$param_old_value = $this->_model()->_param_old_value;
		foreach (array('title', 'description', 'keywords') as $p)
		{
			if ( ! isset($page_info[$p])) continue;
			
			$page_info[$p] = strtr($seo_word->{$p}, array($param_old_value => $page_info[$p]));
		}
		
		return $page_info;
	}
	
	/**
	 * Lay seo_word phu hop voi url
	 * 
	 * @param unknown $url
	 * @return object|false
	 */
	public function get_match_url($url)
	{
		// Get list theo page de giam memory phong truong hop table co qua nhieu row
		$total 		= $this->_model()->get_total();
		$page_size 	= 1000;
		$page_total = ceil($total / $page_size);
		
		$input = array();
		$input['select'] = 'id, url';
		for ($p = 1; $p <= $page_total; $p++)
		{
			$limit = ($p - 1) * $page_size;
			$input['limit'] = array($limit, $page_size);
			
			$list = $this->_model()->get_list($input);
			foreach ($list as $row)
			{
				$row->url = handle_content($row->url, 'output');
				if (url_in_list($url, array($row->url)))
				{
					$row = $this->_model()->get_info($row->id);
					$row->url = handle_content($row->url, 'output');
					
					return $row;
				}
			}
		}
		
		return FALSE;
	}
	
}