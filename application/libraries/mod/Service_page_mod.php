<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Service_page_mod extends MY_Mod
{
	/**
	 * Them cac thong tin phu
	 *
	 * @param object $row
	 * @return object
	 */
	public function add_info($row)
	{
		if (isset($row->url))
		{
			if ($row->url)
			{
				$row->url = handle_content($row->url, 'output');
				$row->url = prep_url($row->url);
			}
			else 
			{
				$row = $this->url($row);
				$row->url = $row->_url_view;
			}
		}
		
		if (isset($row->desc))
		{
			$row->desc = handle_content($row->desc, 'output');
		}
	
		return $row;
	}

	/**
	 * Tao url
	 *
	 * @param object $row
	 * @return object
	 */
	public function url($row)
	{
		$name = url_title(convert_vi_to_en($row->name));
		
		$row->_url_view = site_url("service/page/{$row->id}/{$name}");
		
		return $row;
	}
	
}