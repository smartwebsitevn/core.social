<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class News_widget extends MY_Widget {
	
	/**
	 * Ham khoi dong
	 */
	function __construct()
	{
		// Tai cac file thanh phan
		$this->load->model('news_model');
		$this->lang->load('site/news');
	}
	/**
	 * The loai
	 */
	public function cat_news($cat_id, $news_id = 0, $size = 6, $temp = '')
	{

		// Tao filter
		$filter = array(
			'cat_news' => $cat_id
		);
		if($news_id > 0)
		{
			$filter['!id'] = $news_id;
		}
		$input = array();
		$input['order'] = array('news.created', 'desc');
		$input['limit'] = array(0, $size);

		$this->_create_list($filter, $input);

		// Hien thi view
		$temp = ( ! $temp) ? 'tpl::_widget/news/cat_news' : $temp;
		$this->load->view($temp, $this->data);
	}

	/**
	 * Tin moi
	 */
	function newest($size = 3, $temp = '')
	{
		// Lay tin tieu diem
		$filter = array();
		$input = array();
		$input['order'] = array('news.created', 'desc');
		$input['limit'] = array(0, $size);
		
		$this->_create_list($filter, $input);
		
		// Hien thi view
		$temp = ( ! $temp) ? 'tpl::_widget/news/feature' : $temp;
		$this->load->view($temp, $this->data);
	}
	/**
	 * Tin noi bat
	 */
	function feature($size = 3, $temp = '')
	{
		// Lay tin tieu diem
		$filter = array();
		$filter['feature'] = TRUE;
		
		$input = array();
		$input['order'] = array('news.feature', 'desc');
		$input['limit'] = array(0, $size);
		
		$this->_create_list($filter, $input);
		
		// Hien thi view
		$temp = (!$temp) ? 'tpl::_widget/news/feature' : $temp;
		$this->load->view($temp, $this->data);
	}	
	/**
	 * Tao danh sach hien thi
	 */
	function get($filter=array(),$size = 5)
	{
			// Lay tin tieu diem
		
		$input = array();
		$input['order'] = array('news.created', 'desc');
		$input['limit'] = array(0, $size);
	   	$list = $this->news_model->filter_get_list($filter, $input);
	   	//echo $this->db->last_query();
		foreach ($list as $row)
		{
			$row = t('mod')->news->add_info($row);
			$row = site_create_url('news', $row);
		}
		return $list;
	}
	/**
	 * Tao danh sach hien thi
	 */
	function _create_list($filter, $input)
	{
		$list = $this->news_model->filter_get_list($filter, $input);
		foreach ($list as $row)
		{
			$row = mod('news')->add_info($row);
			$row = mod('news')->url($row);
		}

		$this->data['list'] = $list;
	}
}