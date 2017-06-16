<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login_history_model extends MY_Model {
	
	var $table = 'login_history';
	
/*
 * ------------------------------------------------------
 *  Main Handle
 * ------------------------------------------------------
 */
	function get_list($input = array())
	{
		$is_admin = FALSE;
		if ($input['where']['login_history.is_admin'] == config('verify_yes', 'main'))
		{
			$is_admin = TRUE;
		}
		
		if (!isset($input['select']) || !$input['select'])
		{
			$input['select'] = 'login_history.id, login_history.user_id, login_history.ip, login_history.time';
			
			if ($is_admin)
			{
				$input['select'] .= ',admin.username AS user_name';
			}
			else
			{
				$input['select'] .= ',user.email AS user_name';
			}
		}
		
		$this->_get_list_set_input($input);
		
		$this->db->from('login_history');
		
		if ($is_admin)
		{
			$this->db->join('admin', 'login_history.user_id = admin.id', 'left');
		}
		else
		{
			$this->db->join('user', 'login_history.user_id = user.id', 'left');
		}
		
		$query = $this->db->get();
		
		return $query->result();
	}
	
	function get_total($where = array())
	{
		$is_admin = FALSE;
		if ($where['login_history.is_admin'] == config('verify_yes', 'main'))
		{
			$is_admin = TRUE;
		}
	
		$this->db->where($where);
		
		$this->db->from('login_history');
	
		if ($is_admin)
		{
			$this->db->join('admin', 'login_history.user_id = admin.id', 'left');
		}
		else
		{
			$this->db->join('user', 'login_history.user_id = user.id', 'left');
		}
		
		return $this->db->count_all_results();
	}
	
	/**
	 * Ham duoc goi khi du lieu thay doi
	 */
	function _event_change($act, $params)
	{
		switch ($act)
		{
			// Create
			case 'create':
			{
				// Don dep du lieu
				$this->cleanup();
				
				break;
			}
		}
	}
	
	
/*
 * ------------------------------------------------------
 *  Filter Handle
 * ------------------------------------------------------
 */
	function filter_get_where($filter)
	{
		$where = array();
	
		if (isset($filter['is_admin']))
		{
			$where['login_history.is_admin'] = $filter['is_admin'];
		}
		
		if (isset($filter['user']))
		{
			$where['login_history.user_id'] = $filter['user'];
		}
		
		if (isset($filter['user_name']))
		{
			if ($filter['is_admin'])
			{
				$this->search('admin', 'username', $filter['user_name']);
			}
			else
			{
				$this->search('user', 'email', $filter['user_name']);
			}
		}
		
		if (isset($filter['ip']))
		{
			$where['login_history.ip'] = $filter['ip'];
		}
		
		if (isset($filter['time']))
		{
			$where['login_history.time >='] = $filter['time'];
			$where['login_history.time <='] = $filter['time'] + 24*60*60;
		}
		
		return $where;
	}
	
	function filter_get_list($filter, $input = array())
	{
		$input['where'] = $this->filter_get_where($filter);
		
		return $this->get_list($input);
	}
	
	function filter_get_total($filter)
	{
		$where = $this->filter_get_where($filter);
		
		return $this->get_total($where);
	}
	
	
/*
 * ------------------------------------------------------
 *  Other Fun
 * ------------------------------------------------------
 */
	/**
	 * Lay thong tin dang nhap lan cuoi
	 */
	function get_last($type, $id, $limit = 0)
	{
		$is_admin = ($type == 'admin') ? 'yes' : 'no';
		$is_admin = config('verify_'.$is_admin, 'main');
		
		$filter = array();
		$filter['is_admin'] = $is_admin;
		$filter['user'] 	= $id;
		
		$input = array();
		$input['select'] 	= 'login_history.time, login_history.ip';
		$input['order'] 	= array('login_history.id', 'desc');
		$input['limit'] 	= array($limit, 1);
		
		$list = $this->filter_get_list($filter, $input);
		$row = (isset($list[0])) ? $list[0] : FALSE;
		
		return $row;
	}
	
	/**
	 * Don dep du lieu
	 */
	function cleanup()
	{
		// Xoa cua user cach day 1 thang
		$where = array();
		$where['is_admin'] = config('verify_no', 'main');
		$where['time <'] = now() - 30*24*60*60;
		$this->del_rule($where);
		
		// Xoa cua admin cach day 1 nam
		$where = array();
		$where['is_admin'] = config('verify_yes', 'main');
		$where['time <'] = now() - 365*24*60*60;
		$this->del_rule($where);
	}
	
}
?>