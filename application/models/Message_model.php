<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class message_model extends MY_Model {
	
	var $table = 'message';
	var $key = 'id';
	
	public $relations = array(
	    'user' => 'one',
	);
	

	function _filter_get_where(array $filter)
	{
	    $where = parent::_filter_get_where($filter);
	
	    foreach (array('id', 'user') as $p)
	    {
	        $f = (in_array($p, array('user'))) ? $p.'_id' : $p;
	        $f = 'message.'.$f;
	        $this->_filter_set_where($filter, $p, $f, $where);
	    }
	//pr($filter);

		if (isset($filter['admin_readed']))
		{
			$where['message.admin_readed'] = ($filter['admin_readed'] == 'readed') ? 1 : 0;
		}
	    if (isset($filter['send_admin']))
	    {
	        $where['message.send_admin'] = ($filter['send_admin']) ? 1 : 0;
	    }
	    if (isset($filter['is_spam']))
	    {
	        $where['message.is_spam'] = ($filter['is_spam']) ? 1 : 0;
	    }
	    if (isset($filter['created<']))
	    {
	        $where['message.created <='] = $filter['created<'];
	    }
	    
	    if (isset($filter['title']))
	    {
	        $this->search('message', 'title', $filter['title']);
	    }
	    
	    if (isset($filter['created']))
	    {
	        $where['message.created >='] = $filter['created'][0];
	        $where['message.created <'] = $filter['created'][1];
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
	        switch ($f)
	        {
	            case 'created':
	                {
	                    $created_to = $input['created_to'];
	                    $v = (strlen($created_to)) ? array($v, $created_to) : $v;
	                    $v = get_time_between($v);
	                    $v = ( ! $v) ? NULL : $v;
	                    break;
	                }
	        }
	        	
	        if ($v === NULL) continue;
	        	
	        $filter[$f] = $v;
	    }
	
	    return $filter;
	}
	
	
	/**
	 * Tim kiem du lieu
	 */
	function _search($field, $key)
	{
	    switch ($field)
	    {
	        case 'title':
	            {
	                $this->db->like($this->table.'.title', $key);
	                break;
	            }
	    }
	}
}