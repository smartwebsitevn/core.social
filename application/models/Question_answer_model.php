<?php
class question_answer_model extends MY_Model
{
	var $table = 'question_answer';
	
 /**
	 * Main handle
	 */
	function get_list($input = array(), $lang_id = NULL)
	{
		if (!isset($input['select']) || !$input['select'])
		{
			$input['select']  = 'question_answer.*,';
			$input['select'] .= 'user.name as user_name,user.email as user_email';
		}

		$this->_get_list_set_input($input);
		
		$this->db->from('question_answer');
	    $this->db->join('user', 'user.id = question_answer.user_id', 'left');
	    
		$query = $this->db->get();
		
		return $query->result();
	}
/*
 * ------------------------------------------------------
 *  Filter Handle
 * ------------------------------------------------------
 */
	function _filter_get_where($filter)
	{
		$where = parent::_filter_get_where($filter);



		if (isset($filter['user_id']))
		{
			$where[$this->table . '.user_id'] = $filter['user_id'];
		}
		//== Thuoc tinh bool dang so - chuoi
		foreach (array('status','readed') as $f) {
			if (isset($filter[$f])) {
				$v = ($filter[$f]);//? 'on' : 'off';
				if (is_numeric($v))
					$v = $v ? 1: 0;
				else{
					if ($v == 'off' || $v == 'no' )
						$v = 0;
					else
						$v = 1;
				}
				// echo 'status_' . $v;
				$where[$this->table . '.' . $f] = $v;
			}
		}

		//=== Su ly loc theo ngay tao
		//  1: tu ngay  - den ngay
		if (isset($filter['created']) && isset($filter['created_to'])) {
			$where[$this->table . '.created >='] = is_numeric($filter['created']) ? $filter['created'] : get_time_from_date($filter['created']);
			$where[$this->table . '.created <='] = is_numeric($filter['created_to']) ? $filter['created_to'] : get_time_from_date($filter['created_to']) + 24 * 60 * 60;// phai cong them 1 ngay de thoi gian no la cuoi cua ngay hien thoi
		} //2: tu ngay
		elseif (isset($filter['created'])) {
			$where[$this->table . '.created >='] = is_numeric($filter['created']) ? $filter['created'] : get_time_from_date($filter['created']);
		} //3: den ngay
		elseif (isset($filter['created_to'])) {
			$where[$this->table . '.created <='] = is_numeric($filter['created_to']) ? $filter['created_to'] : get_time_from_date($filter['created_to']) + 24 * 60 * 60;// phai cong them 1 ngay de thoi gian no la cuoi cua ngay hien thoi
		}


		//== Hien thi ra phia nguoi dung
		if (isset($filter['show'])) {
			$where[$this->table . '.status'] = 1;
		}
		//pr($where);
		return $where;
	}

	
/*
 * ------------------------------------------------------
 *  Other Fun
 * ------------------------------------------------------
 */
	/**
	 * Lay tong so phan hoi chua duoc duyet
	 */
	function get_total_unread()
	{
		$filter['status'] = FALSE;
		
		return $this->filter_get_total($filter);
	}
}
?>