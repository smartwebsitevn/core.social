<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class tag_widget extends MY_Widget {

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
	function _list($table_id = 0, $table = '', $temp = '', $data = array())
	{
		if(!$table_id) return false;
		if(!$table)
			$table = strtolower(t()->uri->rsegment(1));

		$this->data = $data;
		$where['where']["`id` in (select `tag_id` from `tag_value` where `type` = '".$table."' and `table_id` = ".$table_id.")"] = null;
		if(!isset($where['order']))
			$where['order'] = array('id','asc');
		// Lay tong so

		$list = $this->_model()->get_list($where);
		// Xu ly list
		foreach ($list as $row)
		{
			$row = $this->_mod()->url($row, $table);
		}
		if(!$list) return true;
		$this->data['list'] = $list;

		$this->_display($this->_make_view($temp, __FUNCTION__));
	}
}