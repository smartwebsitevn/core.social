<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cat_mod extends MY_Mod
{

	/**
	 * Khoi tao doi tuong
	 */
	public function __construct()
	{
		t('config')->load('mod/'.$this->_get_mod(), true, true);
	}

	function add_info($row,$full_info=false)
	{

		$row = parent::add_info($row);

		// su ly noi dung
		//$lang =lang_get_cur();
		//$row->_content = model('cat')->content_get($row->id,$lang->id);

		$row = $this->url($row);
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
		//$name = url_title(convert_vi_to_en($row->name));

		//$row->_url_view = site_url("{$name}-cat{$row->id}");
		$row->_url_view = site_url("cat/{$row->id}");
		$row->_url = $row->_url_view;
		
		return $row;
	}



	/**
	 * Ly danh sach
	 *
	 * @param array $filter
	 * @param array $input
	 * @return array
	 */
	public function get_list_level($level = 0, $parent_id = 0, $type = NULL)
	{
		$list = $this->_model()->get_list_level($level, $parent_id, $type);
		$list = $this->add_info_list($list);
		
		return $list;
	}
	
	/**
	 * Them thong tin cho cac row trong list (De quy)
	 * 
	 * @param array $list
	 * @return array
	 */
	public function add_info_list(array $list)
	{
		foreach ($list as $i => $row)
		{
			$row = $this->url($row);
			$row->_sub = $this->add_info_list($row->_sub);
		}
		
		return $list;
	}

	//=== Tran Type helper
	function check_cat_type($type, $types=NULL)
	{
		// Tai file thanh phan
		if(!$types){
			$types=$this->get_cat_types();
		}
		if(!isset($types[$type]))
			return FALSE;
		return	TRUE;//$types[$type];
	}
	/**
	 * Lay danh sach card type
	 */
	public function get_cat_types()
	{
		$types = $this->config('cat_types');
		return $types;
	}
}