<?php
class Tag_model extends MY_Model {
	
	var $table = 'tag';
	
/*
 * ------------------------------------------------------
 *  Main Handle
 * ------------------------------------------------------
 */
	/**
	 * Them moi
	 */
	function updateTag($name = array(), $table,$table_id, $table_field =null,$otps=array())
	{
		if(!$table_id || !$table) return false;

		// xoa cac ban ghi cu
		$where = array();
		$where['table'] = $table;
		$where['table_id'] = $table_id;
		if($table_field)
			$where['table_field'] = $table_field;
		if ($table_cat = array_get($otps,'table_cat',null))
			$where[] = " `table_cat` ='". $table_cat."'";
		if ($table_type = array_get($otps,'table_type',null))
			$where[] = " `table_type` ='". $table_type."'";
		model('tag_value')->del_rule($where);

		// Kiem tra tag nay da ton tai hay chua
		if(!$name)
			return false;
		$ids = array();
		// them hoac kiem tra tag
		foreach((array)$name as $row){
			$data = array();
			$data['status'] = 1;
			$data['name'] = $row;
			$data['seo_url'] = convert_vi_to_en($row);
			if($info = $this->read(array('where' => $data))){
				$ids[] = $info->id;
			} else {
				$id = 0;
				$this->create($data, $id);
				$ids[] = $id;
			}
		}
		// neu co ids tag thi them vao tag value
		if(!$ids) return true;

		// them moi tag
		foreach($ids as $row){
			$item = array();
			$item['table'] = $table;
			$item['table_id'] = $table_id;
			if($table_field)
				$item['table_field'] = $table_field;
			if($table_cat)
				$item['table_cat'] = $table_cat;
			if($table_type)
				$item['table_type'] = $table_type;
			$item['tag_id'] = $row;
			model('tag_value')->create($item);
		}
		return true;
	}

	/**
	 * get tag
	 */
	function getTag($table_id, $table= null,$otps=array())
	{
		$list =$this->getListTag($table_id, $table,$otps) ;
		$tags=[];
		foreach($list as $row){
			$tags[] = $row->name;
		}
		//pr_db();
		return $tags;
	}
	/**
	 * get tag
	 */
	function getListTag($table_id, $table= null,$otps=array())
	{
		if(!$table_id) return false;
		$table = $table ? $table : strtolower($this->uri->rsegment(1));

		$where = array();
		$where[] = " `table` ='". $table."'";
		$where[] = " `table_id` ='". $table_id."'";
		if ($table_field = array_get($otps,'table_field',null))
			$where[] = " `table_field` ='". $table_field."'";
		if ($table_cat = array_get($otps,'table_cat',null))
			$where[] = " `table_cat` ='". $table_cat."'";
		if ($table_type = array_get($otps,'table_type',null))
			$where[] = " `table_type` ='". $table_type."'";
		$where = implode(' And ',$where);

		$tags = array();
		//	$input['where']["`id` in (select `tag_id` from `tag_value` where `table_id` = '".$table_id."' and `table` = '".$table."')"] = null;
		$input['where']["`id` in (select `tag_id` from `tag_value` where ".$where.")"] = null;

		$list =$this->get_list($input) ;
		//pr_db($list);
		return $list;
	}
	/**
	 * Tim kiem du lieu
	 */
	function _search($field, $key)
	{
		switch ($field)
		{
			case 'name':
			{
				$this->db->where('MATCH(tag.name) AGAINST(\'"'.$this->db->escape_str($key).'"\' IN BOOLEAN MODE)');
				break;
			}
		}
	}
	
	
/*
 * ------------------------------------------------------
 *  Filter Handle
 * ------------------------------------------------------
 */
	function filter_get_where($filter)
	{
		$where = array();
		if (isset($filter['id!']))
		{
			$where['tag.id !='] = $filter['id!'];
		}
		if (isset($filter['name']))
		{
			//$this->search('tag', 'name', $filter['name']);
			$this->db->like('tag.name', $this->db->escape_str($filter['name']));
		}
		//== Thuoc tinh bool dang so - chuoi
		foreach (array('status', 'feature') as $f) {
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
				$where[$this->table . '.' . $f] = $v;
			}
		}
		return $where;
	}
	
	function filter_get_list($filter, $input)
	{
		$input['where'] = $this->filter_get_where($filter);
		return $this->get_list($input);
	}
	
	function filter_get_total($filter)
	{
		$where = $this->filter_get_where($filter);
		
		return $this->get_total($where);
	}

	function _update_tag($id, $type = '')
	{
		$vs = $this->input->post('tag');
		$values = array();
		if($vs) $values = explode(',', $vs);
		$type=  ($type ? $type : strtolower($this->uri->rsegment(1)));
		return $this->updateTag($values,$type ,$id) ;
	}
}
?>