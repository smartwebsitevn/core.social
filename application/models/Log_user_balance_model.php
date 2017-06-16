<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Log_user_balance_model extends MY_Model
{
	public $table = 'log_user_balance';

	public $join_sql = array(
		'user' => 'log_user_balance.user_id = user.id',
	);
	
	public $relations = array(
		'user' => 'one',
	);
	
	public $timestamps = true;
	
	
	/**
	 * Filter handle
	 */
	public function _filter_get_where(array $filter)
	{
		$where = parent::_filter_get_where($filter);
		
		foreach (array('id', 'user_id', 'balance', 'ip', 'created') as $p)
		{
			$f = (in_array($p, array())) ? $p.'_id' : $p;
			$f = $this->table.'.'.$f;
			$m = (in_array($p, array('balance', 'created'))) ? 'range' : '';
			$this->_filter_set_where($filter, $p, $f, $where, $m);
		}

		if (isset($filter['user_email']))
		{
			$this->db->like('user.email', $filter['user_email']);
		}
		
		return $where;
	}
	
	/**
	 * Them moi
	 */
	public function create(array $data, &$insert_id = null)
	{
		//$this->cleanup();
		
		$data = array_add($data, 'url', current_url(true));
		$data = array_add($data, 'ip', t('input')->ip_address());
		$data['created'] = now();
		
		return parent::create($data, $insert_id);
	}
	
	/**
	 * Don dep du lieu
	 */
	public function cleanup($timeout = 0)
	{
		$timeout = max(3*30*24*60*60, $timeout);
		
		$where = array();
		$where['created <'] = now() - $timeout;
		
		$this->del_rule($where);
	}
	
	/**
	 * Luu log
	 * 
	 * @param int 	$user_id
	 * @param float $amount : so tien su ly
	 * @param float $change : loai thay doi
	 * @param float $balance_before : truoc su ly
	 * @param float $balance  : sau su ly
	 * @param array $data
	 */

	public function log($user_id, $balance_before,$balance,$amount,$change,array $data = [])
	{
		$data = array_merge($data,
			compact('user_id', 'balance_before', 'balance', 'amount', 'change'));
		
		return $this->create($data);
	}
	
}