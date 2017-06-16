<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class News_mod extends MY_Mod
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

		foreach (array('title', 'intro', 'meta_desc', 'meta_key') as $p)
		{
			if (isset($row->$p))
			{
				$row->$p = html_escape($row->$p);
			}
		}
		
		if (isset($row->created))
		{
			$row->_created = get_date($row->created);
			$row->_created_time = get_date($row->created, 'time');
		}

		if (isset($row->image_name))
		{
			t('load')->helper('file');
			$row->image = file_get_image_from_name($row->image_name, public_url('img/no_image.png'));
		}
		
		if (isset($row->content))
		{
			$row->content = handle_content($row->content, 'output');
		}
		$row = $this->url($row);

		return $row;
	}
	
	/**
	 * Kiem tra co the thuc hien hanh dong hay khong
	 * 
	 * @param object $row
	 * @param string $action
	 * @return boolean
	 */
	public function can_do($row, $action)
	{
		$result = parent::can_do($row, $action);
		
		switch ($action)
		{
			case 'feature':
			{
				return TRUE;
			}
			
			case 'feature_del':
			{
				$p = preg_replace('#_del$#i', '', $action);
				return ($row->$p) ? TRUE : FALSE;
			}
		}
		
		return $result;
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
		$row->_url_view = site_url("{$name}-news{$row->id}");
		
		return $row;
	}
	
}