<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class album extends MY_Controller
{
	/**
	 * Ham khoi dong
	 */
	public function __construct()
	{
		parent::__construct();
		
		// Tai cac file thanh phan
		$this->lang->load('site/news');
		$this->data['class'] = __CLASS__;
	}
	
	/**
	 * Danh sach
	 */
	public function index()
	{
		page_info('title', lang(__CLASS__));
		$this->data['breadcrumbs'] = array(current_url(), lang(__CLASS__), lang(__CLASS__));
		$this->_display();
	}

	/**
 * The loai
 */
	public function cat($id=0)
	{
		if(!$id)
			show_404();
		if(!isset($this->data['category'.__CLASS__][$id]))
			show_404();
		$this->data['cate'] = $this->data['category'.__CLASS__][$id];
		page_info('title', $this->data['cate']->name);
		page_info('description', $this->data['cate']->description);
		page_info('keywords', $this->data['cate']->keywords);

		$this->data['breadcrumbs'][] = array(current_url(), $this->data['cate']->name, $this->data['cate']->name);
		$this->_display();
	}

	/**
	 * Xem chi tiet
	 */
	public function view($url = '')
	{
		$where['where']['url'] = $url;
		$this->data['info'] = model(__CLASS__)->read($where);
		//pr_db();
		if ( ! $this->data['info'])
		{
			show_404();
		}
		if(!$this->data['info']->public)
		{
			// lay thong tin admin
			$this->load->helper('admin');
			if(!admin_get_account_info())
				show_404();

		}
		$this->data['info'] = $this->_mod()->add_info($this->data['info']);
		// Cap nhat so luot view
		$data = array();
		$data['view'] = $this->data['info']->view + 1;
		$this->_model()->update($this->data['info']->id, $data);

		// lay danh sach hinh anh
		$this->data['info']->images = model('file')->get_list_of_mod(__CLASS__, $this->data['info']->id, 'files');
		foreach($this->data['info']->images as $row){
			$row->image = file_get_image_from_name($row->file_name);
		}
		// Xu ly thong tin cua service
		page_info('title', $this->data['info']->name);
		page_info('description', $this->data['info']->description ?: $this->data['info']->name);
		page_info('keywords', $this->data['info']->keywords ?: $this->data['info']->name);

		$robots = array();
		if($this->data['info']->nofollow)
			$robots[] = 'nofollow noindex';
		page_info('robots', $robots );

		$this->data['cate'] = null;
		if(isset($this->data['category'.__CLASS__][$this->data['info']->cat_id]))
			$this->data['cate'] = $this->data['category'.__CLASS__][$this->data['info']->cat_id];
		if($this->data['cate'])
			$this->data['breadcrumbs'][] = array(site_url($this->data['cate']->_url), $this->data['cate']->name, $this->data['cate']->name);
		$this->data['breadcrumbs'][] = array(current_url(), $this->data['info']->name, $this->data['info']->name);

		$this->_display();
	}
	
}