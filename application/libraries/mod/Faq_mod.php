<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Faq_mod extends MY_Mod
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
		if (isset($row->answer))
		{
			$row->answer = handle_content($row->answer, 'output');
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
		$name = url_title(convert_vi_to_en($row->question));
		
		$row->_url_view = site_url("faq/view/{$row->id}/{$name}");
		
		return $row;
	}

	/**
	 * Lay danh sach
	 *
	 * @param array $filter
	 * @param array $input
	 * @return array
	 */
	public function get_list(array $filter, array $input = array())
	{
		$list = t('model')->faq->filter_get_list($filter, $input);
		foreach ($list as $row)
		{
			$row = $this->add_info($row);
			$row = $this->url($row);
		}
		
		return $list;
	}
	
}