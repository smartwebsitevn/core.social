<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ip_block_model extends MY_Model {
	
	var $table 	= 'ip_block';
	var $key 	= 'ip';
	
	
	/**
	 * Block IP
	 * @param string 	$ip			IP address
	 * @param int 		$timeout	Thoi gian block (0: Block vinh vien)
	 */
	function set($ip, $timeout = 0)
	{
		// Tao thong tin luu vao data
		$data = array();
		$data['expire'] = ($timeout) ? now() + $timeout : 0;
		
		// Lay thong tin
		$info = $this->get_info($ip, $this->key);
		
		// Neu da ton tai thi cap nhat
		if ($info)
		{
			$this->update($ip, $data);
			
		}
		// Neu chua ton tai thi them moi
		else 
		{
			$data['ip'] 		= $ip;
			$data['created']	= now();
			$this->create($data);
		}
	}
	
	/**
	 * Kiem tra IP co bi block hay khong
	 * @return	TRUE: Da bi block || FALSE: Chua bi block
	 */
	function check($ip)
	{
		$info = $this->get_info($ip, 'expire');
		
		return ($info && ( ! $info->expire || $info->expire >= now())) ? TRUE : FALSE;
	}
	
	/**
	 * Ham duoc goi khi du lieu thay doi
	 */
	function _event_change($act, $params)
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
	function _cleanup()
	{
		// Xoa IP het thoi gian block
		$where = array();
		$where['expire >'] = 0;
		$where['expire <'] = now();
		$this->del_rule($where);
	}
	
}
