<?php
class Product_to_view_model extends MY_Model
{
	var $table = 'product_to_view';

	/**
	 * Filter handle
	 */
	function _filter_get_where(array $filter)
	{
		$where = parent::_filter_get_where($filter);

		foreach (array('id','user',  'created', ) as $p)
		{
			$f = (in_array($p, array('user'))) ? $p.'_id' : $p;
			$f = $this->table.'.'.$f;
			$m = (in_array($p, array('created'))) ? 'range' : '';
			$this->_filter_set_where($filter, $p, $f, $where, $m);
		}
		return $where;
	}
}
?>