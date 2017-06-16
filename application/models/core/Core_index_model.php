<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Model Core Index Class
 * 
 * Class model xu ly index du lieu de tim kiem
 * 
 * @author		***
 * @version		2013-07-29
 */
class Core_index_model extends CI_Model {
	
	// Ten table
	var $table 	= '';
	
	// Key chinh cua table table
	var $table_key = 'id';
	
	// Cac field luu tru trong index
	var $fields = array();
	
	// Cac field co query tim kiem rieng
	var $search_special_fields = array();
	
	
/*
 * ------------------------------------------------------
 *  Main handle
 * ------------------------------------------------------
 */
	/**
	 * Import data tu mysql vao index
	 */
	function import()
	{
		// Gan lai thoi gian xu ly fix time out
		ini_set('max_execution_time', 24*60*60);
		
		// Lay data tu mysql
		$input = array();
		foreach ($this->fields as $p)
		{
			$input['select'][] = $this->table.'.'.$p;
		}
		$input['select'] = implode(',', $input['select']);
		$list = $this->{$this->table.'_model'}->get_list($input);
		
		// Ket noi den index
		$index = $this->_connect();
		
		// Them moi index
		foreach ($list as $data)
		{
			$this->_add($index, $data);
		}
		
		// Cap nhat index
		$index->commit();
		$index->optimize();
		
		return $index->count();
	}
	
	/**
	 * Cap nhat index theo danh sach
	 */
	function update_list($list)
	{
		// Gan lai thoi gian xu ly fix time out
		ini_set('max_execution_time', 24*60*60);
		
		// Ket noi den index
		$index = $this->_connect();
		
		// Xu ly danh sach
		foreach ($list as $row)
		{
			// Xoa index cu
			$id = $row->table_id;
			$this->_del($index, $id);
			
			// Xu ly act
			switch ($row->act)
			{
				case 1: // add
				case 2: // update
				{
					$fields = implode(',', $this->fields);
					$info 	= $this->{$this->table.'_model'}->get_info($id, $fields);
					if ($info)
					{
						$this->_add($index, $info);
					}
					
					break;
				}
			}
		}
		
		// Cap nhat index
		$index->commit();
		$index->optimize();
	}
	
	/**
	 * Tim kiem index
	 */
	function search(array $filter, array $limit = array())
	{
		// Ket noi den index
		$index = $this->_connect();
		
		// Set limit
		$index->setResultSetLimit(1024);
		
		// Gan kieu du lieu tim kiem
		Zend_Search_Lucene_Search_QueryParser::setDefaultEncoding('UTF-8');
		
		// Tao query theo filter
		$query = $this->_create_query($filter);
		$query = (is_array($query)) ? implode(' AND ', $query) : $query;
		
		// Thuc hien tim kiem
		$hits = $index->find($query);
		
		return $this->zend_search_lucene_library->result($hits, $limit);
	}
	
	/**
	 * Tao moi index
	 */
	function add($id)
	{
		// Them vao index cho xu ly
		$this->load->model('data_index_model');
		$this->data_index_model->add($this->table, $id, 1);
	}
	
	/**
	 * Cap nhat index
	 */
	function update($id)
	{
		// Them vao index cho xu ly
		$this->load->model('data_index_model');
		$this->data_index_model->add($this->table, $id, 2);
	}
	
	/**
	 * Xoa index
	 */
	function del($id)
	{
		// Them vao index cho xu ly
		$this->load->model('data_index_model');
		$this->data_index_model->add($this->table, $id, 3);
	}
	
	/**
	 * Cap nhat index khi data thay doi
	 */
	function auto_update($id, $data)
	{
		foreach ($this->fields as $p)
		{
			if (isset($data[$p]))
			{
				$this->update($id);
				return TRUE;
			}
		}
		
		return FALSE;
	}
	
	
/*
 * ------------------------------------------------------
 *  Private handle
 * ------------------------------------------------------
 */
	/**
	 * Ket noi den data index
	 */
	protected function _connect()
	{
		$this->load->library('zend_search_lucene_library');
		return $this->zend_search_lucene_library->connect($this->table);
	}
	
	/**
	 * Them moi index
	 */
	protected function _add(&$index, $data){}
	
	/**
	 * Xoa index
	 */
	protected function _del(&$index, $id)
	{
		$hits = $index->find($this->table_key.':"'.$id.'"');
		foreach ($hits as $hit)
		{
			$index->delete($hit->id);
		}
	}
	
	/**
	 * Tao query tim kiem theo filter
	 */
	protected function _create_query($filter)
	{
		return '';
	}
	
	/**
	 * Gan gia tri cho query tu filter
	 */
	protected function _set_query(array $filter, $param, $field, array &$query)
	{
		if (!isset($filter[$param]))
		{
			return;
		}
		
		$v = $filter[$param];
		
		if (in_array($field, $this->search_special_fields))
		{
			$query[] = $this->_get_query($field, $v);
		}
		
		elseif (is_array($v) && count($v) > 1)
		{
			$q = array();
			foreach ($v as $_v)
			{
				$q[] = $this->_get_query($field, $_v);
			}
			
			$query[] = '( '.implode(' OR ', $q).' )';
		}
		
		else 
		{
			$v = (is_array($v) && !count($v)) ? array('') : $v;
			$v = (is_array($v)) ? $v[0] : $v;
			$query[] = $this->_get_query($field, $v);
		}
	}
	
	/**
	 * Tao query tim kiem tuong ung voi cac field
	 */
	protected function _get_query($field, $value)
	{
		$query = $field.':"'.$value.'"';
		$query = '( '.$query.' )';
		
		return $query;
	}
	
}

?>