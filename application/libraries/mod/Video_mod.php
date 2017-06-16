<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class video_mod extends MY_Mod
{
	/**
	 * Them cac thong tin phu
	 * 
	 * @param object $row
	 * @return object
	 */
	public function add_info($row)
	{
		foreach (array('name', 'summary', 'description', 'keywords') as $p)
		{
			if (isset($row->$p))
			{
				$row->$p = html_escape($row->$p);
				$row->$p = handle_content($row->$p , 'output');
			}
		}

		if (isset($row->created))
		{
			$row->_created = get_date($row->created);
			$row->_created_time = get_date($row->created, 'time');
		}
		if (isset($row->updated))
		{
			$row->_updated = get_date($row->updated);
			$row->_updated_time = get_date($row->updated, 'time');
		}
		if (isset($row->image_name))
		{
			if(!$row->image_name && isset($row->video)){
				$value = json_decode($row->video);
				t('load')->helper('youtube');
				$row->image = file_get_image_from_name($row->image_name, getImgYouTube($value[0],'lg'));
			}else {
				t('load')->helper('file');
				$row->image = file_get_image_from_name($row->image_name, public_url('img/no_image.png'));
			}
		}

		if (isset($row->content))
		{
			$row->content = handle_content($row->content, 'output');
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
		$name = 'video/';
		// neu trong admin thi tu dong chen theo ngon ngu
		if(get_area() == 'admin'){
			// neu khong phia la ngon ngu mac dinh thi chen vao
			if($row->lang_id != lang_get_default()->id){
				$name = lang_get_info($row->lang_id)->directory.'/'.$name;
			}
		}
		if($row->url) {
			$name .= $row->url;
		} else {
			$name .= conver_url($row->name);
		}
		$row->_url_view = site_url($name);

		return $row;
	}
	
}