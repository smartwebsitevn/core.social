<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class album_mod extends MY_Mod
{
	public $name = 'album';
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
			t('load')->helper('file');
			$row->image = file_get_image_from_name($row->image_name, public_url('img/no_image.png'));
		}
		
		if (isset($row->content))
		{
			$row->content = handle_content($row->content, 'output');
		}
		
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
		$name = $this->name.'/';
		// neu trong admin thi tu dong chen theo ngon ngu
		/*if(get_area() == 'admin'){
			// neu khong phia la ngon ngu mac dinh thi chen vao
			if($row->lang_id != lang_get_default()->id){
				$name = lang_get_info($row->lang_id)->directory.'/'.$name;
			}
		}*/
		if($row->cat_id && isset($this->data['category'.$this->name][$row->cat_id])) {
			$name = $this->data['category' . $this->name][$row->cat_id]->_url . '/';
		}
		if($row->url) {
			$name .= $row->url;
		} else {
			$name .= convert_vi_to_en($row->name);
		}
		$row->_url_view = site_url($name);

		return $row;
	}
}