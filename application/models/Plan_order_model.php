<?php
class plan_order_model extends MY_Model
{
	var $table = 'plan_order';
	var $primary_key = 'tran_id';
	
	function get_list($input = array())
	{
		if (!isset($input['select']) || !$input['select'])
		{
			$input['select'] = 'plan_order.*,
					            ,tran.user_id AS tran_user_id,tran.amount as tran_amount,tran.created as tran_created,tran.status as tran_status
								,plan.name AS plan_name,plan.cost as plan_cost,plan.discount as plan_discount,plan.day as plan_day
							';
		}
	
		$this->get_list_set_input($input);
	
		$this->db->from('plan_order');
		$this->db->join('plan', 'plan_order.plan_id = plan.id', 'left');
		$this->db->join('tran', 'plan_order.tran_id = tran.id', 'left');
		
		$query = $this->db->get();
		return $query->result();
	}
	
	function get_info($input = array()){
		if (!isset($input['select']) || !$input['select'])
		{
			$input['select'] = 'plan_order.*,
					            ,tran.user_id AS tran_user_id,tran.amount as tran_amount,tran.created as tran_created,tran.status as tran_status
								,plan.name AS plan_name,plan.cost as plan_cost,plan.discount as plan_discount,plan.day as plan_day
							';
		}
		
		$this->get_list_set_input($input);
		
		$this->db->from('plan_order');
		$this->db->join('plan', 'plan_order.plan_id = plan.id', 'left');
		$this->db->join('tran', 'plan_order.tran_id = tran.id', 'left');
		
		$query = $this->db->get();
		if ($query->num_rows())
		{
			return $query->row();
		}
		
		return FALSE;
	}

	function get_total($input = array()){
		if (!isset($input['select']) || !$input['select'])
		{
			$input['select'] = 'plan_order.*,
					            ,tran.user_id AS tran_user_id,tran.amount as tran_amount,tran.created as tran_created,tran.status as tran_status
								,plan.name AS plan_name,plan.cost as plan_cost,plan.discount as plan_discount,plan.day as plan_day
							';
		}
	
		$this->get_list_set_input($input);
	
		$this->db->from('plan_order');
		$this->db->join('plan', 'plan_order.plan_id = plan.id', 'left');
		$this->db->join('tran', 'plan_order.tran_id = tran.id', 'left');
	   
		return $this->db->count_all_results();
	}
	
	
	/*
	 * ------------------------------------------------------
	*  Filter handle
	* ------------------------------------------------------
	*/
	function filter_get_where($filter)
	{
		$where = array();
	
		if (isset($filter['tran_id']))
		{
			$where['plan_order.tran_id'] = $filter['tran_id'];
		}
		
		if (isset($filter['plan_id']))
		{
			$where['plan_order.plan_id'] = $filter['plan_id'];
		}
		if (isset($filter['plan_id']))
		{
			$where['plan_order.plan_id'] = $filter['plan_id'];
		}
		
		
		if (isset($filter['tran_user_id']))
		{
			$where['tran.user_id'] = $filter['tran_user_id'];
		}
		
		if (isset($filter['created']))
		{
			$where['tran.created >='] = $filter['created'];
			$where['tran.created <'] = $filter['created'] + 24*60*60;
		}
		if (isset($filter['tran_status']))
		{
			$where['tran.status'] = $filter['tran_status'];
		}
		return $where;
	}
	
	function filter_get_list($filter, $input = array())
	{
		$input['where'] = $this->filter_get_where($filter);
	
		return $this->get_list($input);
	}
	
	function filter_get_info($filter, $input  = array())
	{
		$input['where'] = $this->filter_get_where($filter);
	
		return $this->get_info($input);
	}
	
	function filter_get_total($filter)
	{
	
		$where = $this->filter_get_where($filter);
	    $input['where'] = $where;
		return $this->get_total($input);
	}
	
	
	
}
?>