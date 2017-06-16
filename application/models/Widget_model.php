<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Widget_model extends MY_Model {
	
	public $table 	= 'widget';
	public $order 	= array('widget.sort_order', 'asc');
	public $select = 'widget.id, widget.name, widget.module, widget.widget, widget.region, widget.layout, widget.status, widget.sort_order';
	public $fields_type_list = array('url_show', 'url_hide', 'setting');
	public $translate_auto = TRUE;
	public $translate_fields = array('name');
	
	public $join_sql = array(
		'module'	=> 'widget.module = module.key',
	);
	
	
/*
 * ------------------------------------------------------
 *  Main handle
 * ------------------------------------------------------
 */
	/**
	 * Filter handle
	 */
	function _filter_get_where(array $filter)
	{
		$where = parent::_filter_get_where($filter);
		
		// Table widget
		foreach (array('id', 'module', 'widget', 'region', 'layout') as $p)
		{
			$f = (in_array($p, array())) ? $p.'_id' : $p;
			$f = 'widget.'.$f;
			$m = (in_array($p, array())) ? 'range' : '';
			$this->_filter_set_where($filter, $p, $f, $where, $m);
		}
		
		if (isset($filter['status']))
		{
			$v = ($filter['status']) ? 'on' : 'off';
			$where['widget.status'] = config('status_'.$v, 'main');
		}
		
		if (isset($filter['name']))
		{
			$this->search('widget', 'name', $filter['name']);
		}
		
		if (isset($filter['module_widget']))
		{
			$module = explode(':', $filter['module_widget'], 2);
			$widget = (isset($module[1])) ? $module[1] : '';
			$module = $module[0];
			
			$where['widget.module'] = $module;
			$where['widget.widget'] = $widget;
		}
		//pr($where);
		return $where;
	}
	
	/**
	 * Tao filter tu input
	 */
	function filter_create($fields, &$input = array())
	{
		// Lay config
		$status = config('status', 'main');
		
		// Lay gia tri cua filter dau vao
		$input = array();
		foreach ($fields as $f)
		{
			$v = $this->input->get($f);
			$v = security_handle_input($v, in_array($f, array()));
			
			if (
				($f == 'status' && ! in_array($v, $status))
			)
			{
				$v = '';
			}
			
			$input[$f] = $v;
		}
		
		if ( ! empty($input[$this->key]))
		{
			foreach ($input as $f => $v)
			{
				$input[$f] = ($f != $this->key) ? '' : $v;
			}
		}
		
		// Tao bien filter
		$filter = array();
		$query 	= url_build_query($input, TRUE);
		foreach ($query as $f => $v)
		{
			switch ($f)
			{
				case 'status':
				{
					$v = config('status_'.$v, 'main');
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
			case 'name':
			{
				$this->db->like('widget.name', $key);
				break;
			}
		}
	}
	
	
/*
 * ------------------------------------------------------
 *  Setting handle
 * ------------------------------------------------------
 */
	/**
	 * Luu setting
	 * @param int 	$widget_id		Widget ID
	 * @param array $setting		Gia tri setting
	 */
	function setting_set($widget_id, array $setting)
	{
		$data = array();
		$data['setting'] = $setting;
		$data = $this->handle_data_input($data);
		
		$this->update($widget_id, $data);
	}
	
	/**
	 * Lay setting
	 * @param int 	 $widget_id		Widget ID
	 * @param string $param			Bien muon lay gia tri, mac dinh la lay toan bo cac bien
	 */
	function setting_get($widget_id, $param = '')
	{
		// Lay info
		$info = $this->get_info($widget_id, 'setting');
		$info = ($info) ? (object)$this->handle_data_output((array)$info) : FALSE;
		$setting = ($info) ? $info->setting : FALSE;
		
		// Neu chi lay gia tri cua 1 bien
		if ($param != '')
		{
			return (isset($setting[$param])) ? $setting[$param] : FALSE;
		}
		
		return $setting;
	}
	
	
/*
 * ------------------------------------------------------
 *  Handle data handle
 * ------------------------------------------------------
 */
	/**
	 * Xu ly du lieu dau vao
	 */
	function handle_data_input(array $data)
	{
		foreach (array('url_show', 'url_hide') as $p)
		{
			if ( ! isset($data[$p])) continue;
			
			foreach ($data[$p] as $i => $v)
			{
				$data[$p][$i] = handle_content($v, 'input');
			}
		}
		
		$data = parent::handle_data_input($data);
		
		return $data;
	}
	
	/**
	 * Xu ly du lieu xuat ra
	 */
	function handle_data_output(array $data)
	{
		$data = parent::handle_data_output($data);
		foreach (array('url_show', 'url_hide') as $p)
		{
			if ( ! isset($data[$p]) || !$data[$p]) continue;
			foreach ($data[$p] as $i => $v)
			{
				$data[$p][$i] = handle_content($v, 'output');
			}
		}
		
		return $data;
	}

	/**
	 * ke the lai trong my model
	 * them cac feld vao
	 */
	function translate($row, $lang_id){
		//return parent::translate($row, $lang_id);
		// Neu can dich 1 list
		if (is_array($row))
		{
			foreach ($row as $i => $r)
			{
				$row[$i] = $this->translate($r, $lang_id);
			}

			return $row;
		}

		$row = (object)$this->handle_data_output((array)$row);

		$module = $row->module;
		// Lay danh sach cac bien setting
		$setting_params = $this->module->{$module}->widget_get_config();
		$setting_params = $setting_params[$row->widget]['setting'];

		// Lay cac field can dich
		$fields = array();
		foreach($setting_params as $k => $r){
			// them vao field ca dich
			if(isset($r['translate']) && $r['translate']) {
				$fields[] = $k;
			}
		}

		foreach ($this->translate_fields as $f)
		{
			if (isset($row->$f))
			{
				$fields[] = $f;
			}
		}

		// Neu khong co field nao can dich
		if ( ! count($fields))
		{
			$row = (object)$this->handle_data_input((array)$row);
			return $row;
		}

		// Neu khong ton tai ban dich nao
		$this->load->model('translate_model');
		$translate = $this->translate_model->get($this->table, $row->{$this->key}, $fields, $lang_id);
		if ( ! $translate)
		{
			$row = (object)$this->handle_data_input((array)$row);
			return $row;
		}

		// Gan gia tri cua cac ban dich
		foreach ($this->translate_fields as $f)
		{
			if(isset($translate[$f][$lang_id]))
				$row->$f = $translate[$f][$lang_id];
		}
		foreach($setting_params as $k => $r){
			// them vao field ca dich
			if(isset($r['translate']) && $r['translate'] && isset($translate[$k][$lang_id])) {
				$row->setting[$k] = $translate[$k][$lang_id];
			}
		}
		$row = (object)$this->handle_data_input((array)$row);
		return $row;
	}
}