<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_model extends MY_Model {
	
	var $table = 'admin';

	
/*
 * ------------------------------------------------------
 *  Main Handle
 * ------------------------------------------------------
 */
	/**
	 * Lay ten cua user
	 */
	public function get_name($id)
	{
		if ( ! $id)
		{
			return '-';
		}

		$info = $this->get_info($id, 'name');

		return ($info) ? $info->name : '';
	}

	/**
	 * Filter handle
	 */
	function _filter_get_where(array $filter)
	{
		$where = parent::_filter_get_where($filter);
		
		foreach (array('id') as $p)
		{
			$f = (in_array($p, array())) ? $p.'_id' : $p;
			$f = 'admin.'.$f;
			$this->_filter_set_where($filter, $p, $f, $where);
		}
		
		if (isset($filter['username']))
		{
			$this->_search('username', $filter['username']);
		}
		
		return $where;
	}
	
	/**
	 * Tim kiem du lieu
	 */
	function _search($field, $key)
	{
		switch ($field)
		{
			case 'username':
			{
				$this->db->like('admin.username', $key);
				//$this->db->where('MATCH(host_plan.name) AGAINST('.$this->db->escape($key).')');
				//$this->db->where('MATCH(host_plan.name) AGAINST(\'"'.$this->db->escape_str($key).'"\' IN BOOLEAN MODE)');
				break;
			}
		}
	}
	
	
/*
 * ------------------------------------------------------
 *  Matrix Handle
 * ------------------------------------------------------
 */
	/**
	 * Tao the xac thuc
	 */
	function matrix_create($id)
	{
		// Tao matrix
		$this->load->library('matrix_library');
		$matrix = $this->matrix_library->create();
		
		// Cap nhat vao data
		$data = array();
		$data['matrix'] = serialize($matrix);
		$this->update($id, $data);
	}
	
	/**
	 * Lay the xac thuc
	 */
	function matrix_get($id)
	{
		$info = $this->get_info($id, 'matrix');
		$matrix = ($info) ? @unserialize($info->matrix) : '';
		$matrix = (!is_array($matrix)) ? array() : $matrix;
		
		return $matrix;
	}

	/*
     * ------------------------------------------------------
     *  Balance handle
     * ------------------------------------------------------
     */


	/**
	 * Lay balance cua user
	 */
	public function balance_get($id)
	{
		$admin = $this->get_info($id, 'balance');

		if (empty($admin->balance))
		{
			return 0;
		}

		$balance = $this->balance_encrypt('decode', $id, $admin->balance);

		return $balance;
	}


	/**
	 *  Tang so du
	 *
	 * @param int 	$id
	 * @param float $amount
	 * @return float	So du cua user sau khi thuc hien thay doi
	 */
	public function balance_plus($id, $amount)
	{
		$balance_bf = $this->balance_get($id);

		$amount = (float) $amount;
		$balance_af=$balance_bf;
		if ($amount)
		{
			$balance_af = $balance_bf + $amount;

			$this->_balance_set($id, $balance_bf,$balance_af,$amount,'+');
		}

		return $balance_af;
	}
	/**
	 * Giam  so du
	 *
	 * @param int 	$id
	 * @param float $amount
	 * @return float	So du cua user sau khi thuc hien thay doi
	 */
	public function balance_minus($id, $amount)
	{
		$balance_bf = $this->balance_get($id);

		$amount = (float) $amount;

		$balance_af=$balance_bf;
		if ($amount)
		{
			$balance_af = $balance_bf - $amount;

			$this->_balance_set($id, $balance_bf,$balance_af,$amount,'-');
		}

		return $balance_af;
	}
	/**
	 * Cap nhat balance cua user
	 */
	private function _balance_set($id, $balance_bf,$balance_af,$amount,$change)
	{
		$data = array();
		$data['balance'] = $this->balance_encrypt('encode', $id, $balance_af);
		$data['balance_decode'] = $balance_af;
		$this->update($id, $data);
		/*if(config('log_user_balance')){
			model('log_user_balance')->log($id, $balance_bf,$balance_af,$amount,$change);
		}*/
	}
	/**
	 * Xu ly ma hoa balance cua admin
	 *
	 * @param string 	$act
	 * @param int 		$id
	 * @param float 	$balance
	 * @return float
	 */
	public function balance_encrypt($act, $id, $balance)
	{
		$this->load->library('encrypt');

		// Tao key ma hoa
		$key = config('encryption_key', '').$id;

		// Ma hoa
		if ($act == 'encode')
		{
			$balance = floatval($balance);
			$balance = $this->encrypt->encode($balance, $key);
		}

		// Giai ma
		elseif ($act == 'decode')
		{
			$balance = $this->encrypt->decode($balance, $key);

			// Neu balance sau khi giai ma khong phai la dang float
			/* if ( ! preg_match('/^-?[0-9]+\.?[0-9]*$/', $balance))
			{
				$balance = 0;
			} */

			$balance = floatval($balance);
		}

		return $balance;
	}

}