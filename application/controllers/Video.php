<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class video extends MY_Controller
{
	/**
	 * Ham khoi dong
	 */
	public function __construct()
	{
		parent::__construct();
		
		// Tai cac file thanh phan
		//$this->lang->load('site/);
		$this->data['class'] = __CLASS__;
	}
	
	/**
	 * Danh sach
	 */
	public function index()
	{
		page_info('title', lang(__CLASS__));
		page_info('breadcrumbs', array(current_url(), lang(__CLASS__), lang(__CLASS__)));
		$this->_display();
	}
	/**
	 * Xem chi tiet
	 */
	public function view($url = '')
	{
		$where['where']['url'] = $url;
		$this->data['info'] = model(__CLASS__)->read($where);
		if ( ! $this->data['info'] || !$this->data['info']->status)
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
		$this->load->helper('youtube');
		$this->lang->load('site/video');
		$this->data['info'] = $this->_mod()->add_info($this->data['info']);
		$this->data['info'] = $this->_mod()->url($this->data['info']);
		$this->data['info']->video = json_decode($this->data['info']->video);

		// Cap nhat so luot view
		$data = array();
		$data['view'] = $this->data['info']->view + 1;
		$this->_model()->update($this->data['info']->id, $data);

		$this->data['info']->image = file_get_image_from_name($this->data['info']->image_name);


		// Xu ly thong tin cua news
		page_info('title', $this->data['info']->name);
		page_info('description', $this->data['info']->description ?: $this->data['info']->name);
		page_info('keywords', $this->data['info']->keywords ?: $this->data['info']->name);
		$robots = array();
		if($this->data['info']->nofollow)
			$robots[] = 'nofollow noindex';
		page_info('robots', $robots );

		$breadcrumbs[] = array(site_url(__CLASS__), lang(__CLASS__), lang(__CLASS__));
		$breadcrumbs[] = array(current_url(), $this->data['info']->name, $this->data['info']->name);
		page_info('breadcrumbs', $breadcrumbs );
		$this->_display();
	}

}