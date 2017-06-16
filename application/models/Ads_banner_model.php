<?php
class Ads_banner_model extends MY_Model
{
	var $table = 'ads_banner';
	//var $order = array('ads_location.name,sort_order', 'asc');
	var $order = array('sort_order', 'asc');
	/**
	 * Main handle
	 */
	function get_list($input = array())
	{
		if (!isset($input['select']) || !$input['select'])
		{
			$input['select'] = 'ads_banner.id,ads_banner.name, ads_banner.content, ads_banner.ads_location_id, ads_banner.image_id, ads_banner.image_name, ads_banner.url, ads_banner.sort_order, ads_banner.status, ads_banner.end, ads_banner.created,
								ads_banner.count_view,ads_banner.count_click,
								ads_location.name AS ads_location_name
							';
		}
		
		$this->_get_list_set_input($input);
		
		$this->db->from('ads_banner');
		$this->db->join('ads_location', 'ads_banner.ads_location_id = ads_location.id', 'left');
		$query = $this->db->get();
		
		return $query->result();
	}
	function _filter_get_where(array $filter)
	{
		$where = parent::_filter_get_where($filter);

		foreach (array('id', 'ads_location_id') as $p)
		{
			$f = (in_array($p, array())) ? $p.'_id' : $p;
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

}
