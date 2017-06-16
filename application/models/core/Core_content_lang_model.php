<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Model Core Content Lang Class
 *
 * Class xu ly noi dung da ngon ngu
 *
 * @author		sontung0@gmail.com
 * @version		2013-07-24
 * @updated		2016-02-24 | phamkhanhcuong@yahoo.com
 */
class Core_content_lang_model extends CI_Model {

	var $key 	= '';
	var $table 	= '';
	var $field_lang 	= 'lang_id';

	// Danh sach cac field can xu ly gia tri
	var $fields_handle = array();


	/**
	 * Luu thong tin
	 */
	function set($table_id, $lang_content)
	{
		foreach ($lang_content as $lang_id => $content)
		{
			// Xu ly input
			$content = $this->_content_handle($content, 'input');

			// Kiem tra $table_id va $lang_id da ton tai hay chua
			$where = array();
			$where[$this->key] 	= $table_id;
			$where[$this->field_lang] 			= $lang_id;
			$this->db->where($where);
			$this->db->select($this->field_lang);
			$total = $this->db->get($this->table)->num_rows();

			// Neu da ton tai thi cap nhat
			if ($total)
			{
				$this->db->where($where);
				$this->db->update($this->table, $content);
			}
			// Neu chua ton tai thi them moi
			else
			{
				$content[$this->key] 	= $table_id;
				$content[$this->field_lang] 			= $lang_id;
				$this->db->insert($this->table, $content);
			}
		}
	}

	/**
	 * Lay thong tin
	 */
	function get($table_id, $lang_id = NULL, $field = '')
	{
		$where = array();
		$where[$this->key] = $table_id;
		if ($lang_id !== NULL)
		{
			$where[$this->field_lang] = $lang_id;
		}
		$this->db->where($where);

		$field = ($field) ? $field.','.$this->field_lang : $field;
		$this->db->select($field);

		$query = $this->db->get($this->table);
		$list = $query->result();

		$result = array();
		foreach ($list as $row)
		{
			$row = $this->_content_handle($row, 'output');

			$r = new stdClass();
			foreach ($row as $p => $v)
			{
				if (in_array($p, array($this->key, $this->field_lang)))
				{
					continue;
				}

				$r->$p = $v;
			}
			$result[$row->lang_id] = $r;
		}

		// Neu chi lay thong tin cua 1 lang
		if ($lang_id !== NULL)
		{
			return (isset($result[$lang_id])) ? $result[$lang_id] : FALSE;
		}

		return $result;
	}

	/**
	 * Xoa du lieu
	 */
	function del($table_id, $lang_id = NULL)
	{
		$where = array();
		$where[$this->key] = $table_id;
		if ($lang_id !== NULL)
		{
			$where[$this->field_lang] = $lang_id;
		}

		$this->db->where($where);
		$this->db->delete($this->table);
	}

	/**
	 * Xu ly gia tri cua cac field
	 */
	protected function _content_handle($content, $act)
	{
		foreach ($this->fields_handle as $f)
		{
			if ($act == 'input' && isset($content[$f]))
			{
				$content[$f] = handle_content($content[$f], $act);
			}
			elseif ($act == 'output' && isset($content->{$f}))
			{
				$content->{$f} = handle_content($content->{$f}, $act);
			}
		}

		return $content;
	}

}
