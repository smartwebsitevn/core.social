<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lang_file_model extends MY_Model {
	
	var $table = 'lang_file';
	var $order = array('file', 'asc');


	/**
	 * Kiem tra ton tai
	 *
	 * @param string $key
	 * @return boolean
	 */
	public function get($file)
	{
		return $this->get_info_rule(array('file'=>$file));
	}

	/**
	 * Luu thong tin
	 *
	 * @param string $key
	 * @param array $data
	 */
	public function import($file)
	{
		$data =array();
		//echo '<br>'.$file;
		$info = $this->get($file);
		if ($info)
		{
			$data['updated']=now();
			$this->update($info->id, $data);
		}
		else
		{
			$data['file'] = $file;
			$data['created']=now();
			$data['updated']=$data['created'];

			$id =0;
			$this->create($data,$id);
			$info = $this->get_info($id);
		}
		return  $info;
	}

	/**
	 * Filter handle
	 */
	function _filter_get_where(array $filter)
	{
		$where = parent::_filter_get_where($filter);

		foreach (array( 'created') as $p)
		{
			$f = (in_array($p, array())) ? $p.'_id' : $p;
			$f = $this->table.'.'.$f;
			$m = (in_array($p, array('created'))) ? 'range' : '';
			$this->_filter_set_where($filter, $p, $f, $where, $m);
		}

		if (isset($filter['file']))
		{
			$this->search($this->table, 'file', $filter['file']);
		}

		return $where;
	}

	/**
	 * Tao filter tu input
	 */
	function filter_create($fields, &$input = array())
	{
		// Lay gia tri cua filter dau vao
		$input = array();
		foreach ($fields as $f)
		{
			$v = $this->input->get($f);
			$v = security_handle_input($v, in_array($f, array()));

			$input[$f] = $v;
		}

		if ( ! empty($input['id']))
		{
			foreach ($input as $f => $v)
			{
				$input[$f] = ($f != 'id') ? '' : $v;
			}
		}


		// Tao bien filter
		$filter = array();
		$query 	= url_build_query($input, TRUE);

		foreach ($query as $f => $v)
		{
			switch ($f)
			{
				case 'created':
				{
					$created_to = $input['created_to'];
					$v = (strlen($created_to)) ? array($v, $created_to) : $v;
					$v = get_time_between($v);
					$v = ( ! $v) ? NULL : $v;
					break;
				}
			}

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
			case 'file':
			{
				$this->db->like($this->table.'.file', $key);
				break;
			}
		}
	}

}