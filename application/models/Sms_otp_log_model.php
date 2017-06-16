<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sms_otp_log_model extends MY_Model
{
	public $table = 'sms_otp_log';
	public $timestamps = true;
	
	/**
	 * Filter handle
	 */
	public function _filter_get_where(array $filter)
	{
	    $where = parent::_filter_get_where($filter);
	
	    foreach (array(
	        'id', 'user','phone','status','created','type','mod'
	    ) as $p)
	    {
	        $f = (in_array($p, array('user'))) ? $p.'_id' : $p;
	        $f = $this->table.'.'.$f;
	        $m = (in_array($p, array('created'))) ? 'range' : '';
	        $this->_filter_set_where($filter, $p, $f, $where, $m);
	    }

	    if (isset($filter['message']) && $filter['message'] != '')
	    {
	        $this->_search('message', $filter['message']);
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
	        case 'message':
	            {
	                $this->db->like($this->table.'.message', $key);
	                break;
	            }
	    }
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

