<?php

class combo_model extends MY_Model
{
	public $table = 'combo';
	public $timestamps       = true;
	
	public $translate_auto   = TRUE;
	public $translate_fields = array('name', 'description');
	public $order	         = array( array('sort_order', 'asc'),array('id', 'desc'));
	//public $table_info       = array('pservice');
	//public $join_sql         = array('combo_pservice');
	
	/**
	 * Filter handle
	 */
	public function _filter_get_where(array $filter)
	{
		$where = parent::_filter_get_where($filter);

		foreach ([
			'id', 'data_type', 'status', 'created', 'expire_to'
		] as $p)
		{
			$f = in_array($p, []) ? $p.'_id' : $p;
			$f = $this->table.'.'.$f;
			$m = in_array($p, ['created', 'expire_to']) ? 'range' : '';
			$this->_filter_set_where($filter, $p, $f, $where, $m);
		}
		if (isset($filter['ids']))
		{
			$this->db->where_in($this->table.'.'.'id',$filter['ids']);
		}
		if (isset($filter['image']))
		{
			$where[$this->table . '.image_id >' ] = '0';
		}
		foreach (array( 'feature' ) as $key)
		{
			if( isset($filter[$key]) && $filter[$key] != -1 )
				$where[$this->table.'.'.$key] = $filter[$key];
		}
		//neu chi kiem tra cai con han
		if(isset($filter['unexpire']))
		{
		    $where['expire_to >=']   = now();
		    $where['expire_from <='] = now(); 
		}
		
		return $where;
	}

	function get_unexpire($id)
	{
		$where['id']   =$id;
		$where['expire_to >=']   = now();
		$where['expire_from <='] = now();

		return $this->get_info_rule($where);
	}
	/**
	 * Lay danh sach cac dịch vụ theo nhóm
	 */
	function get_services($combo)
	{
		if(!$combo->services) return;
		$products=$lessons= [];
		if(isset($combo->services->products) && $combo->services->products) {
			foreach($combo->services->products as $id){
				$product = mod('product')->get_info($id);
				if($product){
					$products[] = $product;
				}
			}
		}
		if(isset($combo->services->lessons) && $combo->services->lessons) {
			foreach($combo->services->lessons as $id){
				$lesson = mod("product")->get_info($id);
				if($lesson){
					$lessons[] = $lesson;
				}
			}
		}
	    return compact('products','lessons');
	}
}