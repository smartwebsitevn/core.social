<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class message_receive_model extends MY_Model {
	
	var $table = 'message_receive';
	var $key = 'id';
	
	public $relations = array(
	    'user' => 'one',
	);
	public $join_sql = array('message');

	/**
	 * Main handle
	 */
	function get_list_receive($input = array())
	{
	    if (!isset($input['select']) || !$input['select'])
	    {
	        $input['select'] = 'message_receive.*,
	                            user.username AS receive_username,
							';
	    }
	
	    $this->_get_list_set_input($input);
	
	    $this->db->from('message_receive');
	    $this->db->join('user', 'message_receive.receive_id = user.id');
	
	    $query = $this->db->get();
	
	    return $query->result();
	}
	
	
	/**
	 * Main handle
	 */
	function get_list($input = array())
	{
	    if (!isset($input['select']) || !$input['select'])
	    {
	        $input['select'] = 'message_receive.*,
	                            message.user_id, message.title, message.content, message.created,
	                            user.name AS sender_name, user.username AS sender_username, user.phone AS sender_phone,
							';
	    }
	
	    $this->_get_list_set_input($input);
	
	    $this->db->from('message_receive');
	    $this->db->join('message', 'message_receive.message_id = message.id');
	    $this->db->join('user', 'message.user_id = user.id');
	     
	    $query = $this->db->get();
	
	    return $query->result();
	}
	
	/**
	 * Main handle
	 */
	function get_info($id, $field = '')
	{
	    if (!$field)
	    {
	        $input['select'] = 'message_receive.*,
	                            message.user_id, message.title, message.content, message.created,
	                            user.name AS sender_name,user.username AS sender_username, user.phone AS sender_phone,
							';
	    }
	
	    
	    $this->_get_list_set_input($input);
	
	    $this->db->from('message_receive');
	    $this->db->join('message', 'message_receive.message_id = message.id');
	    $this->db->join('user', 'message.user_id = user.id');
	    $this->db->where(array('message_receive.id' => $id));
	    
	    $query = $this->db->get();
	
	    if ($query->num_rows())
		{
			$row = $query->row();
			return $row;
		}
		
		return FALSE;
	}
	
	
	/**
	 * Lay tong so
	 */
	function get_total($where = array())
	{
	    // Gan where
	    $this->db->where($where);
	    $this->db->from($this->table);
	    $this->db->join('message', 'message_receive.message_id = message.id');
	    $this->db->join('user', 'message.user_id = user.id');
	    
	
	    // Neu chi loc du lieu tren table hien tai
	    return $this->db->count_all_results();
	}
	
	function _filter_get_where(array $filter)
	{
	    $where = parent::_filter_get_where($filter);
	
	    foreach (array('message', 'receive') as $p)
	    {
	        $f = (in_array($p, array('receive', 'message'))) ? $p.'_id' : $p;
	        $f = 'message_receive.'.$f;
	        $this->_filter_set_where($filter, $p, $f, $where);
	    }

	    if (isset($filter['title']))
	    {
	        $this->search('message', 'title', $filter['title']);
	    }
	    
	    if (isset($filter['user']))
	    {
	        $where['message.user_id'] = $filter['user'];
	    }
	    if (isset($filter['readed']))
	    {
			if(is_array($filter['readed'])){
				$where['message_receive.readed >='] = $filter['readed'][0];
				$where['message_receive.readed <'] = $filter['readed'][1];
			}
			else{
				if ($filter['readed'])
				{
					$where['message_receive.readed !='] = '';
				}
				else
				{
					$where['message_receive.readed'] = '';
				}
			}

	    }
	    
	    if (isset($filter['created']))
	    {
	        $where['message.created >='] = $filter['created'][0];
	        $where['message.created <'] = $filter['created'][1];
	    }
	
	    return $where;
	}
}