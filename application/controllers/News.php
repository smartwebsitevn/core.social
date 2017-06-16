<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class News extends MY_Controller
{
	/**
	 * Ham khoi dong
	 */
	public function __construct()
	{
		parent::__construct();
		
		// Tai cac file thanh phan
		$this->lang->load('site/news');
	}
	
	/**
	 * Danh sach
	 */
	public function index()
	{
		$this->_create_list();
		
		page_info('title', lang('title_news_list'));
		
		$this->_display();
	}
	
	/**
	 * Tim kiem
	 */
	public function _search()
	{
		// Tao filter
		$filter_input 	= array();
		$filter_fields 	= array('title');
		$filter = $this->_model()->filter_create($filter_fields, $filter_input);
		$this->data['filter'] = $filter_input;
		
		// Tao base url
		$base_url = current_url().'?'.url_build_query($filter_input);
		
		// Tao danh sach
		$input = array();
		$this->_create_list($filter, $input, $base_url);
		
		page_info('title', lang('title_news_search'));
		
		$this->_display();
	}

	/**
	 * tag
	 */
	public function tag($id = 0)
	{
		if(!$id || !$tag = model('tag')->get_info($id))
			show_404();

		$filter['tag'] = array($tag->id, strtolower(__CLASS__));
		$this->_create_list($filter);
		page_info('title', $tag->name);

		$this->data['tag'] = $tag;
		$this->_display();
	}
	
	/**
	 * The loai
	 */
	public function cat()
	{
		// Lay thong tin
		$cat_id = $this->uri->rsegment(3);
		$cat_id = ( ! is_numeric($cat_id)) ? 0 : $cat_id;
		$cat = model('news_cat')->get_info($cat_id);
		if ( ! $cat)
		{
			redirect();
		}
		
		// Tao filter
		$filter = array(
			'cat_news' => $cat->id
		);
		
		// Tao base url
		$base_url = current_url().'?';

		// Tao danh sach
		$this->_create_list($filter, array(), $base_url);
		
		$this->data['cat'] = $cat;
		
		page_info('title', $cat->titleweb ? $cat->titleweb : $cat->name);
		page_info('description', $cat->description ? $cat->description : $cat->name);
		page_info('keywords', $cat->keywords ? $cat->keywords : $cat->name);
		$this->_display();
	}
	
	/**
	 * Tao danh sach hien thi
	 */
	protected function _create_list($filter = array(), $input = array(), $base_url = '')
	{
		// Lay tong so
		$total = $this->_model()->filter_get_total($filter);

		$page_size = 20;//module_get_setting('news', 'list_limit');
		$limit=0;
		if($total>0){
		$limit = $this->input->get('per_page');
		$limit = max(0, min($limit, get_limit_page_last($total, $page_size)));
		}
		// Lay danh sach
		$input['limit'] = array($limit, $page_size);
		$filter['status'] ='on';
		$list = $this->_model()->filter_get_list($filter, $input);
		//pr_db();
		// Xu ly list
		foreach ($list as $row)
		{
			$row = $this->_mod()->add_info($row);
			$row = $this->_mod()->url($row);
		}
		$this->data['list'] = $list;
		
		// Tao chia trang
		$pages_config = array();
		$pages_config['page_query_string'] = TRUE;
		$pages_config['base_url'] 	= ( ! $base_url) ? current_url().'?' : $base_url;
		$pages_config['total_rows'] = $total;
		$pages_config['per_page'] 	= $page_size;
		$pages_config['cur_page'] 	= $limit;
		$this->data['pages_config'] = $pages_config;
	}
	
	/**
	 * Xem chi tiet
	 */
	public function view()
	{
		// Lay thong tin
		$id = $this->uri->rsegment(3);
		$id = ( ! is_numeric($id)) ? 0 : $id;
		$news = $this->_model()->get_info_rule(array('id'=>$id));
		if ( ! $news)
		{
			redirect();
		}
		if($news->status != 1 ){
			// lay thong tin admin
			$this->load->helper('admin');
			if(!admin_get_account_info())
				show_404();
		}
		// Cap nhat so luot view
		$data = array();
		$data['count_view'] = $news->count_view + 1;
		$this->_model()->update($id, $data);

		// Xu ly thong tin cua news
		$news = $this->_mod()->add_info($news);
		$news = $this->_mod()->url($news);
		$this->data['news'] = $news;
		page_info('breadcrumbs', array($news->_url_view, word_limiter($news->title, 10), $news->title));
		page_info('title', $news->titleweb ? $news->titleweb : $news->title);
		page_info('description', $news->description ? $news->description : $news->title);
		page_info('keywords', $news->keywords ? $news->keywords : $news->title);

		$this->_display();
	}
	
}