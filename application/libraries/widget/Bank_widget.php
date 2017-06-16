<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class bank_widget extends MY_Widget {

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
		$filter = array();
		$filter['show'] = true;
		$this->data['list'] = mod('bank')->get_list($filter);

		$this->_display($this->_make_view($temp, __FUNCTION__));
	}
}