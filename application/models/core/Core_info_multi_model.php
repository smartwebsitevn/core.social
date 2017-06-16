<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Model Core Info Multi Value Class
 *
 * Class model xu ly thong tin nhieu gia tri
 *
 * @author		sontung0@gmail.com
 * @version		2015-08-08
 */
class Core_info_multi_model extends CI_Model {

	// Ten table
	var $table 	= '';

	// Key chinh cua table table
	var $table_key = 'id';


	/**
	 * Luu thong tin
	 */
	function set($info, $table_id, $info_values, array $attributes = array())
	{
		// Xoa cac du lieu cu
		$this->del($info, $table_id);

		// Loai bo cac gia tri trung nhau
		$info_values = (!is_array($info_values)) ? array($info_values) : $info_values;
		$info_values = array_unique($info_values);

		// Tao du lieu moi
		foreach ($info_values as $v)
		{
			// Neu khong ton tai gia tri
			$v = trim($v);
			if (!strlen($v)) continue;

			// Them vao data
			$data = $attributes;
			$data[$this->table.'_'.$this->table_key] = $table_id;
			$data[$info.'_id'] = $v;
			$this->db->insert($this->table.'_'.$info, $data);
		}

		// Goi ham callback cua table_model
		$where = array();
		$where[$this->table_key] = $table_id;

		$data = array();
		$data[$info] = $info_values;

		$this->{$this->table.'_model'}->_event_change('update', array($where, $data));
	}

	/**
	 * Xoa thong tin
	 */
	function del($info, $table_id, $info_id = NULL)
	{
		$where = array();
		$where[$this->table.'_'.$this->table_key] = $table_id;
		if ($info_id !== NULL)
		{
			$where[$info.'_id'] = $info_id;
		}

		$this->db->where($where);
		$this->db->delete($this->table.'_'.$info);
	}

	/**
	 * Lay thong tin
	 */
	function get($info, $table_id)
	{
		$where = array();
		$where[$this->table.'_'.$this->table_key] = $table_id;
		$this->db->where($where);

		$this->db->select($info.'_id');
		$query = $this->db->get($this->table.'_'.$info);

		$values = array();
		foreach ($query->result() as $row)
		{
			$values[] = $row->{$info.'_id'};
		}

		return $values;
	}

	/**
	 * Lay danh sach thong tin (Bao gom ca id va name)
	 */
	//$info_join duoc su dung trong truong hop bang chua thong tin khong cung ten voi bang info da khai bao
	function get_list($info, $table_id, $select = '',$info_join=null)
	{
		$where = array();
		$where[$this->table.'_'.$this->table_key] = $table_id;
		$this->db->where($where);

		$select = ( ! $select) ? "{$info}.id, {$info}.name" : $select;
		$this->db->select($select);

		$this->db->from($this->table.'_'.$info);
		if($info_join)
			$this->db->join($info_join, "{$this->table}_{$info}.{$info}_id = {$info_join}.id", 'left');
		else
			$this->db->join($info, "{$this->table}_{$info}.{$info}_id = {$info}.id", 'left');
		$query = $this->db->get();

		return $query->result();
	}

	/**
	 * Kiem tra $info_id co ton tai trong danh sach thong tin cua $table_id hay khong
	 * @param string 	$info		Ten thong tin
	 * @param int 		$table_id	Table id
	 * @param int 		$info_id	Id cua thong tin can kiem tra
	 */
	function exists($info, $table_id, $info_id)
	{
		$where = array();
		$where[$this->table.'_'.$this->table_key] = $table_id;
		$where[$info.'_id'] = $info_id;
		$this->db->where($where);

		if ($this->db->get($this->table.'_'.$info)->num_rows())
		{
			return TRUE;
		}

		return FALSE;
	}

}
