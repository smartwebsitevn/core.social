<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class Attribute_model extends MY_Model {

	
	public $table 	= 'attribute';
	public $order	= array( array('sort_order', 'desc'), array('id', 'desc'));
	public $translate_auto = TRUE;
	public $translate_fields = array(
		'name'
	);

	

	

/*
 * ------------------------------------------------------
 *  Main handle
 * ----------------------------------------------------
 */

	/**
	 * Filter handle
	 */

	function _filter_get_where(array $filter)
	{
		$where = parent::_filter_get_where($filter);
		
		if (isset($filter['id']))
		{
			if( is_array($filter['id']) )
				$this->db->where_in($this->table.'.id', $filter['id']);
			else
				$where[$this->table.'.'.'id'] = $filter['id'];
		}

		
		if (isset($filter['group_id']))
			$where[$this->table.'.'.'group_id'] = $filter['group_id'];
		

		if (isset($filter['name']))
			$this->search($this->table, 'name', $filter['name']);
		
		// Bo loc trong trang danh sach san pham
		if (isset($filter['product_count']))
		{
			// Dem so luong san pham tuong ung
			$this->db->select('attribute.*, COUNT(product.id) as product_num');
			$this->db->group_by('attribute.id');

			if (isset($filter['category_id']))
			{
				$this->search($this->table, 'category_id', $filter['category_id']);
			}
			else
			{
				$this->db->join('product_to_attribute', 'product_to_attribute.attribute_id = ' . $this->table . '.id', 'inner');
				$this->db->join('product', 'product.id = product_to_attribute.product_id', 'inner');
				$this->db->where('product.show', 1);
				if( isset($filter['product.name']) )
					$this->db->like('product.name', $filter['product.name']);
				if( isset($filter['product.tag']) )
				{
					$this->db->join('tag_value', 'tag_value.table_id = product.id', 'inner');
					$this->db->where('tag_value.table', 'product');
					$this->db->where('tag_value.table_field', 'tags');
					$this->db->where('tag_value.tag_id', $filter['product.tag']);
				}
			}
		}
		


		if (isset($filter['show']))
		{
			if( $filter['show'] != -1 )
			$where[$this->table.'.'.'show'] = $filter['show'];
		}
		
		return $where;
	}

	

	/**
	 * Tao filter tu input
	 */
	function filter_create($fields, &$input = array())
	{
		/* Lay gia tri cua filter dau vao */
		$input = array();
		foreach ($fields as $f)
		{
			$v = $this->input->get($f);
			$v = security_handle_input($v, in_array($f, array()));
			
			$input[$f] = $v;
		}

		

		/* Tao bien filter */
		$filter = array();
		$query 	= url_build_query($input, TRUE);
		foreach ($query as $f => $v)
		{
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
			case 'name':
			{
				$this->db->like($this->table.'.'.$field, $key);
				break;
			}

			case 'category_id':
			{
				$this->db->join('product_to_attribute', 'product_to_attribute.attribute_id = ' . $this->table . '.id', 'inner');
				$this->db->join('product', 'product.id = product_to_attribute.product_id', 'inner');
				$this->db->join('product_to_category', 'product_to_category.product_id = product.id', 'inner');
				if( is_array($key) )
					$this->db->where_in( 'product_to_category.category_id', $key);
				else
					$this->db->where( 'product_to_category.category_id', $key );
				break;
				$this->db->where('product.show', 1);
			}
		}
	}

}