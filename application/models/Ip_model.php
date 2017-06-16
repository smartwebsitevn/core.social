<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ip_model extends MY_Model {
	
	var $table 	= 'ip';
	var $key 	= 'ip';
	
	
/*
 * ------------------------------------------------------
 *  Main handle
 * ------------------------------------------------------
 */
	/**
	 * Luu thong tin cua IP hien tai
	 */
	public function set(array $data)
	{
		// Xu ly data
		$data['last_activity'] = now();
		foreach (array('action_time', 'action_count') as $p)
		{
			if (isset($data[$p]) && is_array($data[$p]))
			{
				$data[$p] = serialize($data[$p]);
			}
		}
		
		// Neu chua ton tai key thi them moi
		$ip 	= $this->input->ip_address();
		$info 	= $this->get_info($ip, 'ip');
		if ( ! $info)
		{
			$data['ip'] = $ip;
			$this->create($data);
		}
		// Neu da ton tai key thi update
		else 
		{
			$this->update($ip, $data);
		}
	}
	
	/**
	 * Lay thong tin cua IP hien tai
	 */
	public function get($param = '')
	{
		// Lay thong tin
		$ip 	= $this->input->ip_address();
		$info 	= $this->get_info($ip);
		
		// Xu ly thong tin
		if ($info)
		{
			foreach (array('action_time', 'action_count') as $p)
			{
				$info->$p = @unserialize($info->$p);
				$info->$p = ( ! is_array($info->$p)) ? array() : $info->$p;
			}
		}
		
		// Neu muon lay gia tri cua 1 bien
		if ($param)
		{
			return (isset($info->$param)) ? $info->$param : FALSE;
		}
		
		return $info;
	}
	
	/**
	 * Ham duoc goi khi du lieu thay doi
	 */
	public function _event_change($act, $params)
	{
		parent::_event_change($act, $params);
		
		switch ($act)
		{
			// Create
			case 'create':
			{
				// Don dep du lieu
				$this->_cleanup();
				
				break;
			}
		}
	}
	
	/**
	 * Don dep du lieu
	 */
	public function _cleanup()
	{
		// Xoa IP time out
		$where = array();
		$where['last_activity <'] = now() - config('ip_time_out', 'main');
		$this->del_rule($where);
	}
	
	
/*
 * ------------------------------------------------------
 *  Action handle
 * ------------------------------------------------------
 */
	/**
	 * Lay value cua action theo type
	 * @param string 	$type		Ten type
	 * @param string 	$action		Ten action (Neu khong khai bao action thi se lay toan bo value)
	 * @return mixed
	 */
	protected function _action_get($type, $action = '')
	{
		$action_type = $this->get('action_'.$type);
		$action_type = ( ! is_array($action_type)) ? array() : $action_type;
		
		if ($action)
		{
			return (isset($action_type[$action])) ? $action_type[$action] : FALSE;
		}
		
		return $action_type;
	}
	
	/**
	 * Luu value cua action theo type
	 * @param string 	$type		Ten type
	 * @param string 	$action		Ten action
	 * @param mixed 	$value		Gia tri
	 * @return null
	 */
	protected function _action_set($type, $action, $value)
	{
		$action_type = $this->_action_get($type);
		$action_type[$action] = $value;
		
		$data = array();
		$data['action_'.$type] = $action_type;
		$this->set($data);
	}
	
	
/*
 * ------------------------------------------------------
 *  Action Time handle
 * ------------------------------------------------------
 */
	/**
	 * Lay time cua action
	 * @param string 	$action		Ten action
	 * @return	int
	 */
	public function action_time_get($action = '')
	{
		return $this->_action_get('time', $action);
	}
	
	/**
	 * Luu time cua action
	 * @param string 	$action		Ten action
	 * @param int 		$time		Timestamp
	 * @return	null
	 */
	public function action_time_set($action, $time = '')
	{
		$time = (int)$time;
		$time = ( ! $time) ? now() : $time;
		
		$this->_action_set('time', $action, $time);
	}
	
	/**
	 * Kiem tra time cua action
	 * @param string 	$action		Ten action
	 * @param int 		$need		Time can phai doi
	 * @param int 		$wait		Time da doi
	 * @return	bool
	 */
	public function action_time_check($action, $need, &$wait = 0)
	{
		$time 	= $this->action_time_get($action);
		$wait 	= now() - $time;
		
		return ($wait >= $need) ? TRUE : FALSE;
	}
	
	
/*
 * ------------------------------------------------------
 *  Action Count handle
 * ------------------------------------------------------
 */
	/**
	 * Lay count cua action
	 * @param string 	$action		Ten action
	 * @return	int
	 */
	public function action_count_get($action = '')
	{
		return $this->_action_get('count', $action);
	}
	
	/**
	 * Luu count cua action
	 * @param string 	$action		Ten action
	 * @param int 		$count		So count
	 * @return	null
	 */
	public function action_count_set($action, $count)
	{
		$count = ( ! is_numeric($count)) ? 0 : $count;
		
		$this->_action_set('count', $action, $count);
	}
	
	/**
	 * Thay doi count cua action
	 * 
	 * @param string 	$action
	 * @param int 		$value
	 * @return int
	 */
	public function action_count_change($action, $value)
	{
		$count = $this->action_count_get($action) + 1;
		
		$this->action_count_set($action, $count);
		
		return $count;
	}
	
}
