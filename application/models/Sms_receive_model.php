<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sms_receive_model extends MY_Model
{
	public $table = 'sms_receive';
	
	public $timestamps = true;
	
	
	/**
	 * Kiem tra sms_id co ton tai hay khong
	 * 
	 * @param string $sms_id
	 * @return boolean
	 */
	public function has_sms($sms_id)
	{
		return ($this->get_id(compact('sms_id')));
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
		$where = array();
		$where['created <'] = now() - 30*24*60*60;
		
		$this->del_rule($where);
	}
	
}