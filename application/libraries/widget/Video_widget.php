<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class video_widget extends MY_Widget {
	
	/**
	 * Ham khoi dong
	 */
	public function __construct()
	{

	}

	/**
	 * @param array $data: du lieu truy van
	 * @param string $temp: file name view
	 * @param array $_data: du lieu tiep noi den view
	 * @param bool|false $paging: cho phep tao phan trang hay khong
	 */
	function _list($where = array(), $temp = '',$paper = false)
	{
		$now = now();

		if(!isset($where['order']))
			$where['order'] = array('created' => 'desc', 'id'=>'desc');
		if(!isset($where['select']))
			$where['select'] = 'id,name,summary,image_name,video,created,url,nofollow,view';
		$where['where']['status'] = 1;
		// Lay tong so
		$total = $this->_model()->total($where);
		$page_size = module_get_setting('video', 'list_limit');
		$page_size = $page_size ? $page_size : 10;
		$limit=0;

		if($total>0){
			$limit = $this->input->get('per_page');
			$limit = max(0, min($limit, get_limit_page_last($total, $page_size)));
		}
		// Lay danh sach
		if(!isset($where['limit']))
			$where['limit'] = array($page_size, $limit);
		//$filter['status'] =1;
		$list = $this->_model()->select($where);
		// Xu ly list
		foreach ($list as $row)
		{
			$row = $this->_mod()->add_info($row);
			$row = $this->_mod()->url($row);
		}
		$this->data['list'] = $list;

		// Tao chia trang
		if($paper) {
			$pages_config = array();
			$pages_config['page_query_string'] = TRUE;
			$pages_config['base_url'] = current_url() . $this->getParamGet();
			$pages_config['total_rows'] = $total;
			$pages_config['per_page'] = $page_size;
			$pages_config['cur_page'] = $limit;
			$this->data['pages_config'] = $pages_config;
		}
		$this->_display($this->_make_view($temp, __FUNCTION__));
	}
	private function getParamGet()
	{
		$url = parse_url(current_url(true));
		if(!isset($url['query']) || !$url['query'])
			return '?';
		parse_str($url['query'], $query);
		if(isset($query['per_page']))
			unset($query['per_page']);
		return  '?'.http_build_query($query);
	}
}