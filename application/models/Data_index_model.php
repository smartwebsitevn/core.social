<?php
class Data_index_model extends MY_Model {
	
	var $table = 'data_index';
	
	
	/**
	 * Them moi index can xu ly
	 */
	function add($table, $table_id, $act)
	{
		// Kiem tra ton tai hay chua
		$where = array();
		$where['table'] 	= $table;
		$where['table_id'] 	= $table_id;
		$where['act'] 		= $act;
		$id = $this->get_id($where);
		
		// Neu chua ton tai thi them moi
		if (!$id)
		{
			$data = array();
			$data['id'] 		= random_string('unique');
			$data['table'] 		= $table;
			$data['table_id'] 	= $table_id;
			$data['act'] 		= $act;
			$data['status'] 	= config('verify_no', 'main');
			$this->create($data);
		}
	}
	
	/**
	 * Lay danh sach cac index can xu ly cua table
	 */
	function get($table, $size = 25)
	{
		$where = array();
		$where['table'] 	= $table;
		$where['status'] 	= config('verify_no', 'main');
		$this->db->where($where);
		
		if ($size)
		{
			$this->db->limit($size, 0);
		}
		
		$query = $this->db->get($this->table);
		
		return $query->result();
	}
	
	/**
	 * Gan trang thai dang xu ly cho list
	 */
	function set_status($list)
	{
		foreach ($list as $row)
		{
			$data = array();
			$data['status'] = config('verify_yes', 'main');
			$data['time']	= now();
			$this->update($row->id, $data);
		}
	}
	
	/**
	 * Khoi phuc cac index xu ly bi loi
	 */
	function restore_index_error()
	{
		// Lay danh sach
		$where = array();
		$where['status'] 	= config('verify_yes', 'main');
		$where['time <='] 	= now() - 24*60*60;
		$this->db->where($where);
		
		$query = $this->db->get($this->table);
		$list = $query->result();
		
		// Xoa va tao moi lai
		foreach ($list as $row)
		{
			$this->del($row->id);
			
			$this->add($row->table, $row->table_id, $row->act);
		}
	}
	
}
?>