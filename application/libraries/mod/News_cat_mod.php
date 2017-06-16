<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class News_cat_mod extends MY_Mod
{
	/**
	 * Tao url
	 *
	 * @param object $row
	 * @return object
	 */
	public function url($row)
	{
		$name = url_title(convert_vi_to_en($row->name));
		
		$row->_url_view = site_url("news/cat/{$row->id}/{$name}");
		
		return $row;
	}

	public function menu_holder_callback($ids)
	{
		$list = $this->get_list(array('ids'=> explode(',',$ids)));
		$holder='';
		foreach($list as $row){
			$holder .='<li><a href="'.$row->_url_view.'">'.$row->name.'</a></li>';
		}
		$holder= '<ul>'.$holder.'</ul>';
		return  $holder;
	}

}