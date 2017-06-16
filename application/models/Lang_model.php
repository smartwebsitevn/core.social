<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lang_model extends MY_Model {

	var $table = 'lang';
	var $order = array('sort_order', 'asc');


	/**
	 * Gan ngon ngu mac dinh
	 */
	/*function set_default($id)
	{
		$default = $this->get_default('default');
		$default = ($default) ? $default->default : 0;

		$data = array();
		$data['default'] 	= $default + 1;
		$data['status'] 	= config('status_on', 'main');
		$this->update($id, $data);
	}*/

	/**
	 * Lay ngon ngu mac dinh
	 */
	/*function get_default($field = '')
	{
		$input = array();
		$input['select'] = $field;
		$input['where']['status'] = config('status_on', 'main');
		$input['order'] = array('default', 'desc');
		$input['limit'] = array('0', '1');
		$default = $this->get_list($input);

		return (isset($default[0])) ? $default[0] : FALSE;
	}*/

	/**
	 * Lay danh sach cac ngon ngu dang hoat dong
	 */
	function get_list_active($field = '')
	{
		$input = array();
		$input['select'] = $field;
		$input['where']['status'] = config('status_on', 'main');

		return $this->get_list($input);
	}

	/**
	 * Lay thong tin cua ngon ngu dang hoat dong
	 */
	function get_info_active($id, $field = '')
	{
		$where = array();
		$where['id'] = $id;
		$where['status'] = config('status_on', 'main');

		return $this->get_info_rule($where, $field);
	}


	function _event_change($act, $params)
	{
		$this->cache_update();
	}
	function cache_update()
	{
		$list = lang_get_list();

		// Luu vao cache
		$data = array();
		foreach($list as $row)
		{
			$data[$row->directory]['id'] = $row->id;
			$data[$row->directory]['is_default'] = $row->is_default;
		}
		//pr($data);
		$data = serialize($data);
		$path = $this->config->item('cache_path');
		$_cache_path = (($path == '') ? APPPATH.'cache'.DS : $path).'lang';
		$this->load->helper('file');
		write_file($_cache_path, $data);
		return $list;
	}



}