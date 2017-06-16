<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cronjob_model extends MY_Model
{
	public $table = 'cronjob';
	//public $order = array('sort_order', 'asc');


	/*
     * ------------------------------------------------------
     *  Main handle
     * ------------------------------------------------------
     */
	/**
	 * Filter handle
	 */
	function _filter_get_where(array $filter)
	{
		$where = parent::_filter_get_where($filter);

		foreach (array('id', 'created') as $p)
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


		return $where;
	}

	/**
	 * Tao filter tu input
	 */
	function filter_create($fields, &$input = array())
	{
		// Lay gia tri cua filter dau vao
		$input = array();
		foreach ($fields as $f)
		{
			$v = $this->input->get($f);
			$v = security_handle_input($v, in_array($f, array()));

			$input[$f] = $v;
		}

		if ( ! empty($input['id']))
		{
			foreach ($input as $f => $v)
			{
				$input[$f] = ($f != 'id') ? '' : $v;
			}
		}

		// Tao bien filter
		$filter = array();
		$query 	= url_build_query($input, TRUE);
		foreach ($query as $f => $v)
		{

			if ($v === NULL) continue;

			$filter[$f] = $v;
		}

		return $filter;
	}

}