<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sitemap extends MY_Controller {
	
	/**
	 * Ham khoi dong
	 */
	function __construct()
	{
		parent::__construct();
		$this->load->helper(array('xml'));
		// Tai cac file thanh phan
	}
	
	
/*
 * ------------------------------------------------------
 *  video list
 * ------------------------------------------------------
 */
	
	function index()
	{
		// load ngày hiện tịa
		$_data['date'] = date('Y-m-d') . "T" . date('H:iP');

		$_data['tablename'] = array('news');
		foreach($_data['tablename'] as $key => $row) {
			foreach($this->data['category'.$row] as $cat){
				$cat->sm = 0.80;
			}
		}
		// load phan mem
		$now = now();
		$where = array();
		$where['select'] = 'id,name,url,cat_id';
		$where['where']['status'] = 1;
		$where['where']['created <='] = $now;
		$where['order'] = array('created' => 'desc','id'=>'desc');
		$where['limit'] = array(1000);
		foreach($_data['tablename'] as $row){
			$_data[$row] = model($row)->select($where);
			foreach($_data[$row] as $table)
			{
				$table->sm = 0.64;
				$table = mod($row)->url($table);
			}
		}

		header("Content-Type: text/xml;charset=UTF-8");
		$this->load->view('site/sitemap/index', $_data);
	}
	function rss($cat_id = 0)
	{
		$this->lang->load('home/sitemap');
		$now = now();

		// load ngày hiện tịa
		$_data['date'] = date('l, F d, Y H:i A');
		// load tin tức
		if(isset($this->data['category'][$cat_id]))
		{
			$subid = $this->data['category'][$cat_id]->_sub_id;
			$subid[] = $cat_id;
			$where['where']['?cat_id'] = $subid;
		}
		$where['select'] = 'id,name,url,cat_id,img_id,comment,summary,updated';
		$where['where']['status'] = 1;
		$where['where']['created <='] = $now;
		$where['where']["((expired > 0 and expired <= ".$now.") or expired = 0)"] = null;
		$where['order'] = array('created' => 'desc','id'=>'desc');
		$where['where']['sm_priority !='] = '0';
		$where['limit'] = array(500);
		$_data['list'] = $this->news_model->select($where);

		$imgs = array();
		foreach($_data['list'] as $row)
		{
			$row->_url = url('news', $row);
			$this->ids[] = $row->id;
			$imgs[] = $row->img_id;
		}
		$imgs = getFileInfo($imgs);
		foreach($_data['list'] as $row)
		{
			$row->img = isset($imgs[$row->img_id]) ? $imgs[$row->img_id] : '';
		}
		
		$_data['infor'] = $this->data['infor'];
		header("Content-Type: text/xml;charset=UTF-8");
		$this->load->view('site/sitemap/rss', $_data);

	}
	
	function view()
	{
		array_unshift($this->data['_head']['title'], 'RSS');
		array_unshift($this->data['_head']['desc'], 'RSS');
		array_unshift($this->data['_head']['keywords'], 'RSS');
		$this->data['cat'] = $this->data['cat_list_level'];
		
		$this->load->view('site/sitemap/view', $this->data);
	}
}