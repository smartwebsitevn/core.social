<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Page_mod extends MY_Mod
{
	/**
	 * Them cac thong tin phu
	 * 
	 * @param object $row
	 * @return object
	 */
	public function add_info($row)
	{
		if (isset($row->intro))
		{
			$row->intro = handle_content($row->intro, 'output');
		}

		if (isset($row->content))
		{
			$row->content = handle_content($row->content, 'output');
		}
		$row=	$this->url($row);
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
		$name = $row->url;
		
		$row->_url_view = site_url("{$name}-page{$row->id}");
		
		return $row;
	}
	
}