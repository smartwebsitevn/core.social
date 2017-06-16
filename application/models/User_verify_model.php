<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH.'models/core/Core_info_key_model.php';

class User_verify_model extends Core_info_key_model
{
	public $table = 'user_verify';
	public $key = 'user_id';
	public $select = 'user_verify.*';
	
	public $relations = array(
		'user' => array('one', 'user_id', 'id'),
	);
	
	
	/**
	 * Luu thong tin
	 */
	public function set_info($user_id, array $data)
	{
		return $this->set($user_id, $data);
	}
	
	/**
	 * Filter handle
	 */
	public function _filter_get_where(array $filter)
	{
		$where = parent::_filter_get_where($filter);
		
		$fs = array();
		$fs['id'] = 'user_id';
		foreach (array('id', 'phone', 'card_no', 'created') as $p)
		{
			$f = (isset($fs[$p])) ? $fs[$p] : $p;
			$f = $this->table.'.'.$f;
			$m = (in_array($p, array('created'))) ? 'range' : '';
			$this->_filter_set_where($filter, $p, $f, $where, $m);
		}
		
		if (isset($filter['name']))
		{
			$this->search('user_verify', 'name', $filter['name']);
		}
		
		return $where;
	}
	
	/**
	 * Tim kiem
	 */
	public function _search($field, $key)
	{
		switch ($field)
		{
			case 'name':
			{
				$this->db->like('user_verify.name', $key);
				break;
			}
		}
	}
	
	/**
	 * Lay paypal_emails cua user
	 * 
	 * @param int $user_id
	 * @return array
	 */
	public function get_paypal_emails($user_id)
	{
		$info = $this->get($user_id, 'paypal_emails');
		
		$val = $info ? @unserialize($info->paypal_emails) : '';
		$val = ( ! is_array($val)) ? array() : $val;
		
		return $val;
	}
	
}