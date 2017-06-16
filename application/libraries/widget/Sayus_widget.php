<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class sayus_widget extends MY_Widget {

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
	function _list($data = array(), $temp = '',$_data = array())
	{
		$data['where']['status'] = 1;
		//$data['where']['created <='] = $now;
		if(!isset($data['order']))
			$data['order'] = array('sort_order' => 'asc','id'=>'desc');
		// Lay tong so
		$total = $this->_model()->total($data);
		$page_size = module_get_setting('sayus', 'list_limit');
		$page_size = $page_size ? $page_size : 10;
		$limit=0;
		if($total>0){
			$limit = $this->input->get('per_page');
			$limit = max(0, min($limit, get_limit_page_last($total, $page_size)));
		}
		// Lay danh sach
		$data['limit'] = array($page_size, $limit);
		//$filter['status'] =1;
		$list = $this->_model()->select($data);
		// Xu ly list
		foreach ($list as $row)
		{
			$row->image = file_get_image_from_name($row->image_name);
		}
		$this->data['list'] = $list;

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