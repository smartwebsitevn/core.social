<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Database Class
 * 
 * Class xu ly luu tru du lieu
 *
 * @author		***
 * @version		2014-01-20
 */
class Db_model extends CI_Model {
	
	// Column types
	var $_types = array('text', 'list');
	
	
	/**
	 * Ham khoi dong
	 */
	function __construct()
	{
		parent::__construct();
		
		// Tai file thanh phan
		$this->load->model('table_model');
		$this->load->model('table_col_model');
		$this->load->model('table_row_model');
	}
	
	
/*
 * ------------------------------------------------------
 *  Table handle
 * ------------------------------------------------------
 */
	/**
	 * Tao table moi
	 * @param string $table		Ten table
	 */
	function create_table($table)
	{
		// Kiem tra table nay da ton tai hay chua
		$table_id = $this->get_table_id($table);
		
		// Neu chua ton tai thi them moi
		if ( ! $table_id)
		{
			$data = array();
			$data['name'] = $table;
			$this->table_model->create($data, $table_id);
		}
		
		return $table_id;
	}
	
	/**
	 * Doi ten table
	 * @param string $table		Ten cu cua table
	 * @param string $new_table	Ten moi cua table
	 */
	function rename_table($table, $new_table)
	{
		// Kiem tra table
		$table_id = $this->get_table_id($table);
		if ( ! $table_id)
		{
			return FALSE;
		}
		
		// Kiem tra new_table
		$new_table_id = $this->get_table_id($new_table);
		if ($new_table_id)
		{
			return FALSE;
		}
		
		// Cap nhat data
		$this->table_model->update_field($table_id, 'name', $new_table);
		
		return TRUE;
	}
	
	/**
	 * Xoa table
	 * @param string $table		Ten table
	 */
	function del_table($table)
	{
		// Kiem tra table
		$table_id = $this->get_table_id($table);
		if ( ! $table_id)
		{
			return FALSE;
		}
		
		// Xoa table
		$this->table_model->del($table_id);
		
		// Xoa cac col cua table
		$where = array();
		$where['table_id'] = $table_id;
		$this->table_col_model->del_rule($where);
		
		// Xoa cac row cua table
		$where = array();
		$where['table_id'] = $table_id;
		$this->table_row_model->del_rule($where);
		
		return TRUE;
	}
	
	/**
	 * Kiem tra table co ton tai hay khong
	 * @param string $table		Ten table
	 */
	function table_exists($table)
	{
		$table_id = $this->get_table_id($table);
		
		return ($table_id) ? TRUE : FALSE;
	}
	
	/**
	 * Lay thong tin table
	 * @param string $table		Ten table
	 */
	function get_table($table)
	{
		$where = array();
		$where['name'] = $table;
		
		return $this->table_model->get_info_rule($where, 'table.*');
	}
	
	/**
	 * Lay table id
	 * @param string $table		Ten table
	 */
	function get_table_id($table)
	{
		$where = array();
		$where['name'] = $table;
		
		return $this->table_model->get_id($where);
	}
	
	/**
	 * Lay danh sach table
	 */
	function get_list_table()
	{
		$tables = array();
		$list = $this->table_model->get_list();
		foreach ($list as $row)
		{
			$tables[$row->id] = $row->name;
		}
		
		return $tables;
	}
	
	
/*
 * ------------------------------------------------------
 *  Column handle
 * ------------------------------------------------------
 */
	/**
	 * Tao column moi
	 * @param string $table		Ten table
	 * @param array  $col		Thong tin column
	 */
	function create_col($table, array $col)
	{
		// Kiem tra col nay da ton tai hay chua
		$col_id = $this->get_col_id($table, $col['name']);
		
		// Neu chua ton tai thi them moi
		if ( ! $col_id)
		{
			$table_id = $this->get_table_id($table);
			if ($table_id)
			{
				$col['table_id'] = $table_id;
				$this->table_col_model->create($col, $col_id);
			}
		}
		
		// Neu da ton tai thi cap nhat
		else 
		{
			$this->table_col_model->update($col_id, $col);
		}
		
		return $col_id;
	}
	
	/**
	 * Doi ten column
	 * @param string $table		Ten table
	 * @param string $col		Ten cu cua col
	 * @param string $new_col	Ten moi cua col
	 */
	function rename_col($table, $col, $new_col)
	{
		// Kiem tra col
		$col_id = $this->get_col_id($table, $col);
		if ( ! $col_id)
		{
			return FALSE;
		}
		
		// Kiem tra new_col
		$new_col_id = $this->get_col_id($table, $new_col);
		if ($new_col_id)
		{
			return FALSE;
		}
		
		// Cap nhat data
		$this->table_col_model->update_field($col_id, 'name', $new_col);
		
		return TRUE;
	}
	
	/**
	 * Xoa column
	 * @param string $table		Ten table
	 * @param string $col		Ten column
	 */
	function del_col($table, $col)
	{
		// Kiem tra col
		$table_id = 0;
		$col_id = $this->get_col_id($table, $col, $table_id);
		if ( ! $col_id)
		{
			return FALSE;
		}
		
		// Xoa col
		$this->table_col_model->del($col_id);
		
		// Xoa cac row cua col
		$where = array();
		$where['table_id'] = $table_id;
		$where['table_col_id'] = $col_id;
		$this->table_row_model->del_rule($where);
		
		return TRUE;
	}
	
	/**
	 * Kiem tra column co ton tai hay khong
	 * @param string $table		Ten table
	 * @param string $col		Ten column
	 */
	function col_exists($table, $col)
	{
		$col_id = $this->get_col_id($table, $col);
		
		return ($col_id) ? TRUE : FALSE;
	}
	
	/**
	 * Lay thong tin column
	 * @param string $table		Ten table
	 * @param string $col		Ten column
	 */
	function get_col($table, $col)
	{
		$where = array();
		$where['table.name'] = $table;
		$where['table_col.name'] = $col;
		
		return $this->table_col_model->get_info_rule($where, 'table_col.*');
	}
	
	/**
	 * Lay column id
	 * @param string $table		Ten table
	 * @param string $col		Ten column
	 */
	function get_col_id($table, $col, &$table_id = 0)
	{
		$col_id = $this->get_col_ids($table, array($col), $table_id);
		$col_id = (isset($col_id[$col])) ? $col_id[$col] : FALSE;
		
		return $col_id;
	}
	
	/**
	 * Lay danh sach column id
	 * @param string $table		Ten table
	 * @param array  $cols		Danh sach ten column
	 */
	function get_col_ids($table, array $cols, &$table_id = 0)
	{
		$filter = array();
		$filter['table'] 	= $table;
		$filter['col'] 		= $cols;
		
		$input = array();
		$input['select'] = 'table_col.id, table_col.name, table_col.table_id';
		
		$col_ids = array();
		$list = $this->table_col_model->filter_get_list($filter, $input);
		foreach ($list as $row)
		{
			$table_id = $row->table_id;
			$col_ids[$row->name] = $row->id;
		}
		
		return $col_ids;
	}
	
	/**
	 * Lay danh sach column cua table
	 * @param string $table		Ten table
	 */
	function get_list_col($table, &$table_id = 0)
	{
		$filter = array();
		$filter['table'] = $table;
		
		$input = array();
		$input['select'] = 'table_col.*';
		
		$cols = array();
		$list = $this->table_col_model->filter_get_list($filter, $input);
		foreach ($list as $row)
		{
			$cols[$row->name] = $row;
			$table_id = $row->table_id;
		}
		
		return $cols;
	}
	
	
/*
 * ------------------------------------------------------
 *  Row handle
 * ------------------------------------------------------
 */
	/**
	 * Them row moi
	 * @param string $table		Ten table
	 * @param array  $row		Thong tin row
	 */
	function insert($table, array $row)
	{
		// Xu ly thong tin row
		$cols 	= $this->get_list_col($table);
		$row 	= $this->_handle_row_input($row, $cols);
		if ( ! count($row))
		{
			return FALSE;
		}
		
		// Tao _id
		$_id = random_string('unique');
		
		// Them vao data
		foreach ($row as $c => $v)
		{
			$data = array();
			$data['value'] 			= $v;
			$data['table_id'] 		= $cols[$c]->table_id;
			$data['table_col_id'] 	= $cols[$c]->id;
			$data['table_row_id'] 	= $_id;
			$this->table_row_model->create($data);
		}
		
		return $_id;
	}
	
	/**
	 * Cap nhat row
	 * @param string $table		Ten table
	 * @param string $_id		Row id
	 * @param array  $row		Thong tin row
	 */
	function update($table, $_id, array $row)
	{
		// Xu ly thong tin row
		$cols 	= $this->get_list_col($table);
		$row 	= $this->_handle_row_input($row, $cols);
		if ( ! count($row))
		{
			return FALSE;
		}
		
		// Cap nhat data
		foreach ($row as $c => $v)
		{
			// Kiem tra col nay da co gia tri hay chua
			$where = array();
			$where['table_id'] 		= $cols[$c]->table_id;
			$where['table_col_id'] 	= $cols[$c]->id;
			$where['table_row_id'] 	= $_id;
			$v_id = $this->table_row_model->get_id($where);
			
			// Neu chua co thi them moi
			if ( ! $v_id)
			{
				$data = $where;
				$data['value'] = $v;
				$this->table_row_model->create($data);
			}
			
			// Neu co roi thi cap nhat
			else 
			{
				$data = array();
				$data['value'] = $v;
				$this->table_row_model->update($v_id, $data);
			}
		}
		
		return TRUE;
	}
	
	/**
	 * Xoa row
	 * @param string $table		Ten table
	 * @param string $_id		Row id
	 */
	function del($table, $_id)
	{
		// Lay table_id
		$table_id = $this->get_table_id($table);
		if ( ! $table_id)
		{
			return FALSE;
		}
		
		// Xoa trong data
		$where = array();
		$where['table_id'] 		= $table_id;
		$where['table_row_id'] 	= $_id;
		$this->table_row_model->del_rule($where);
		
		return TRUE;
	}
	
	/**
	 * Lay thong tin row
	 * @param string $table		Ten table
	 * @param string $_id		Row id
	 */
	function row($table, $_id)
	{
		// Lay thong tin
		$filter = array();
		$filter['table'] 	= $table;
		$filter['col !='] 	= '';
		$filter['_id']		= $_id;
		
		$input = array();
		$input['select'] = 'table_col.name, table_row.value';
		
		$list = $this->table_row_model->filter_get_list($filter, $input);
		if ( ! count($list))
		{
			return FALSE;
		}
		
		// Xu ly thong tin
		$row = array();
		foreach ($list as $r)
		{
			$row[$r->name] = $r->value;
		}
		
		// Xu ly thong tin row
		$cols 	= $this->get_list_col($table);
		$row 	= $this->_handle_row_output($row, $cols);
		
		// Luu id
		$row['_id'] = $_id;
		
		return (object)$row;
	}
	
	/**
	 * Lay danh sach cac row cua table
	 * @param string $table		Ten table
	 * @param array  $order		Sap xep (VD: array('id', 'desc', SORT_NATURAL))
	 */
	function get($table, array $order = array())
	{
		// Lay thong tin
		$filter = array();
		$filter['table'] 	= $table;
		$filter['col !='] 	= '';
		
		$input = array();
		$input['select'] = 'table_col.name, table_row.value, table_row.table_row_id AS _id';
		
		$list = $this->table_row_model->filter_get_list($filter, $input);
		if ( ! count($list))
		{
			return array();
		}
		
		
		// Xu ly thong tin
		$result = array();
		foreach ($list as $r)
		{
			$result[$r->_id][$r->name] = $r->value;
		}
		
		$cols = $this->get_list_col($table);
		foreach ($result as $_id => $row)
		{
			// Xu ly thong tin row
			$row = $this->_handle_row_output($row, $cols);
			
			// Luu id
			$row['_id'] = $_id;
			
			$result[$_id] = (object)$row;
		}
		
		
		// Xu ly order
		if (isset($order[0]) && isset($cols[$order[0]]))
		{
			// Lay input
			$order_col 		= $order[0];
			$order_type 	= (isset($order[1])) ? $order[1] : 'asc';
			$order_flags 	= (isset($order[2])) ? $order[2] : 6 | 8;//SORT_NATURAL | SORT_FLAG_CASE;
			
			// Tao list luu gia tri cua col can sap xep
			$list = array();
			foreach ($result as $_id => $row)
			{
				$list[$_id] = $row->{$order_col};
			}
			
			// Thuc hien sap xep
			if ($order_type == 'asc')
			{
				asort($list, $order_flags);
			}
			elseif ($order_type == 'desc')
			{
				arsort($list, $order_flags);
			}
			
			// Gan lai gia tri cua row tuong ung voi list da sap xep
			foreach ($list as $_id => $v)
			{
				$list[$_id] = $result[$_id];
			}
			
			$result = $list;
		}
		
		return array_values($result);
	}
	
	/**
	 * Lay tong so row cua table
	 * @param string $table		Ten table
	 */
	function get_total($table)
	{
		$filter = array();
		$filter['table'] = $table;
		
		$input = array();
		$input['group_by'] 	= 'table_row.table_row_id';
		$input['select'] 	= 'table_row.table_row_id AS _id';
		
		$list 	= $this->table_row_model->filter_get_list($filter, $input);
		$total 	= count($list);
		
		return $total;
	}
	
	
/*
 * ------------------------------------------------------
 *  Other handle
 * ------------------------------------------------------
 */
	/**
	 * Xu ly thong tin row dau vao
	 * @param array	$row	Thong tin row
	 * @param array	$cols	Danh sach column cua table
	 */
	protected function _handle_row_input(array $row, array $cols)
	{
		// Xu ly gia tri theo col type
		foreach ($row as $c => $v)
		{
			if ( ! isset($cols[$c]))
			{
				unset($row[$c]);
			}
			else 
			{
				$row[$c] = $this->_handle_value_input($v, $cols[$c]->type);
			}
		}
		
		return $row;
	}
	
	/**
	 * Xu ly thong tin row xuat ra
	 * @param array	$row	Thong tin row
	 * @param array	$cols	Danh sach column cua table
	 */
	protected function _handle_row_output(array $row, array $cols)
	{
		// Gan gia tri mac dinh cho cac col khong co gia tri
		foreach (array_keys($cols) as $c)
		{
			$row[$c] = ( ! isset($row[$c])) ? '' : $row[$c];
		}
		
		// Xu ly gia tri theo col type
		foreach ($row as $c => $v)
		{
			$row[$c] = $this->_handle_value_output($v, $cols[$c]->type);
		}
		
		return $row;
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Xu ly value dau vao
	 * @param mixed $value		Gia tri
	 * @param int 	$type		Type cua column
	 */
	protected function _handle_value_input($value, $type)
	{
		$type = (isset($this->_types[$type])) ? $this->_types[$type] : $this->_types[0];
		if ($type == 'list')
		{
			$value = ( ! is_array($value)) ? array($value) : $value;
			$value = serialize($value);
		}
		
		return $value;
	}
	
	/**
	 * Xu ly value xuat ra
	 * @param mixed $value		Gia tri
	 * @param int 	$type		Type cua column
	 */
	protected function _handle_value_output($value, $type)
	{
		$type = (isset($this->_types[$type])) ? $this->_types[$type] : $this->_types[0];
		if ($type == 'list')
		{
			$value = @unserialize($value);
			$value = ( ! is_array($value)) ? array() : $value;
		}
		
		return $value;
	}
	
}