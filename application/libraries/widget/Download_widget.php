<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class download_widget extends MY_Widget {

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
	function _list($where = array(), $temp = '',$paper = false, $data = array())
	{
		$this->data = $data;
		$now = now();

		if(!isset($where['order']))
			$where['order'] = array('order' => 'asc','created' => 'desc', 'id'=>'desc');
		if(!isset($where['select']))
			$where['select'] = 'id,name,image_name,created,url,nofollow,total_sizes, total_files';
		$where['where']['status'] = 1;
		// Lay tong so
		$total = $this->_model()->total($where);
		$page_size = module_get_setting('page', 'list_limit');
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
			$services_config = array();
			$services_config['service_query_string'] = TRUE;
			$services_config['base_url'] = current_url() . $this->getParamGet();
			$services_config['total_rows'] = $total;
			$services_config['per_service'] = $page_size;
			$services_config['cur_service'] = $limit;
			$this->data['services_config'] = $services_config;
		}
		$this->_display($this->_make_view($temp, __FUNCTION__));
	}


	private function getParamGet()
	{
		$url = parse_url(current_url(true));
		if(!isset($url['query']) || !$url['query'])
			return '?';
		parse_str($url['query'], $query);
		if(isset($query['per_service']))
			unset($query['per_service']);
		return  '?'.http_build_query($query);
	}
}