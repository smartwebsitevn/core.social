<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sms_otp_user_model extends MY_Model
{
	public $table = 'sms_otp_user';
	
	/**
	 * Filter handle
	 */
	public function _filter_get_where(array $filter)
	{
	    $where = parent::_filter_get_where($filter);
	
	    foreach (array(
	        'id', 'user','created_last_otp', 'created_last_odp','last_otp', 'last_odp'
	    ) as $p)
	    {
	        $f = (in_array($p, array('user'))) ? $p.'_id' : $p;
	        $f = $this->table.'.'.$f;
	        $m = (in_array($p, array('created_last_otp', 'created_last_odp'))) ? 'range' : '';
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
	
}

