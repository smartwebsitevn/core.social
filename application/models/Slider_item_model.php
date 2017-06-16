<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Slider_item_model extends MY_Model {

	public $table 	= 'slider_item';
	public $order 	= array('sort_order', 'asc');

	public $relations = array(
		'slider' => 'one',
	);



	/**
	 * Filter handle
	 */
	public function _filter_get_where(array $filter)
	{
		$where = parent::_filter_get_where($filter);

		foreach (array('id', 'slider') as $p)
		{
			$f = (in_array($p, array('slider'))) ? $p.'_id' : $p;
			$f = $this->table.'.'.$f;
			$m = (in_array($p, array('created'))) ? 'range' : '';
			$this->_filter_set_where($filter, $p, $f, $where, $m);
		}


		if (isset($filter['status']))
		{
			$v = ($filter['status']) ;//? 'on' : 'off';
			$where[$this->table.'.'.'status'] = config('status_'.$v, 'main');
		}

		if (isset($filter['show']))
		{
			$where[$this->table.'.status'] = 1;
		}
		return $where;
	}

	/**
	 * Lay danh sach items cua slider_id
	 */
	function get($slider_key = '')
	{
		$this->load->helper('file');

		$input = array();
		$input['select'] = 'slider_item.*';
		if ($slider_key)
		{
			$input['where']['slider.key'] = $slider_key;
		}
		$input['where']['slider_item.status'] = 1;
		$this->db->join('slider', 'slider_item.slider_id = slider.id');
		$list = $this->get_list($input);
		foreach ($list as $row)
		{
			$row = mod('slider_item')->add_info($row);
		}

		return $list;
	}
}