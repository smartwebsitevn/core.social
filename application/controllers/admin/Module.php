<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Module extends MY_Controller {
	
	/**
	 * Ham khoi dong
	 */
	function __construct()
	{
		parent::__construct();
		
		// Tai cac file thanh phan
		$this->load->model('module_model');
		$this->lang->load('admin/module');
	}
	
	/**
	 * Remap method
	 */
	function _remap($method)
	{
		if (in_array($method, array('install', 'uninstall', 'edit', 'setting')))
		{
			$this->_action($method);
		}
		elseif (in_array($method, array('table', 'table_update', 'row_add')))
		{
			$this->_table_action($method);
		}
		elseif (in_array($method, array('row_edit', 'row_del')))
		{
			$this->_row_action($method);
		}
		elseif (method_exists($this, $method))
		{
			$this->{$method}();
		}
		else
		{
			show_404('', FALSE);
		}
	}
	
	
/*
 * ------------------------------------------------------
 *  List handle
 * ------------------------------------------------------
 */
	/**
	 * Danh sach
	 */
	function index()
	{
		// Cap nhat sort_order
		if ($this->input->get('act') == 'update_order')
		{
			$items = $this->input->post('items');
			$items = explode(',', $items);
			foreach ($items as $i => $id)
			{
				$this->module_model->update_field($id, 'sort_order', $i+1);
			}
			
			$output = json_encode(array('complete' => TRUE));
			set_output('json', $output);
		}
		
		
		// Lay danh sach da cai dat
		$list_install = array();
		$list_data = $this->module_model->get_list();
		
		$actions = array('setting','edit', 'uninstall');
		foreach ($list_data as $row)
		{
			// Neu khong ton tai thi xoa bo
			if ( ! module_exists($row->key))
			{
				$this->module_model->del($row->key);
				continue;
			}
			
			foreach ($actions as $action)
			{
				$row->{'_url_'.$action} = admin_url('module/'.$action.'/'.$row->key);
				$row->{'_can_'.$action} = ($this->_can_do($row, $action) && admin_permission_url($row->{'_url_'.$action})) ? TRUE : FALSE;
			}
			
			$list_install[$row->key] = $row;
		}
		$this->data['list_install'] = $list_install;
		
		// Lay danh sach chua cai dat
		$list_uninstall = array();
		$list_module = $this->module->get_list();
		
		$actions = array('install');
		foreach ($list_module as $module)
		{
			if (isset($list_install[$module]))
			{
				continue;
			}
			
			$row = new stdClass();
			$row->key 	= $module;
			$row->name 	= $this->module->{$module}->config->item('name');
			
			foreach ($actions as $action)
			{
				$row->{'_url_'.$action} = admin_url('module/'.$action.'/'.$row->key);
				$row->{'_can_'.$action} = ($this->_can_do($row, $action) && admin_permission_url($row->{'_url_'.$action})) ? TRUE : FALSE;
			}
			
			$list_uninstall[$row->key] = $row;
		}
		$this->data['list_uninstall'] = $list_uninstall;
		
		// Luu cac bien gui den view
		$this->data['url_update_order'] = current_url().'?act=update_order';
		
		// Breadcrumbs
		$breadcrumbs = array();
		$breadcrumbs[] = array(admin_url('module'), lang('mod_module'));
		$breadcrumbs[] = array(current_url(), lang('list'));
		$this->data['breadcrumbs'] = $breadcrumbs;
		
		// Hien thi view
		$this->_display();
	}
	
	
/*
 * ------------------------------------------------------
 *  Rules params
 * ------------------------------------------------------
 */
	/**
	 * Gan dieu kien cho cac bien
	 */
	function _set_rules($params = array())
	{
		$rules = array();
		$rules['name'] 			= array('name', 'required|trim|xss_clean');
		$rules['sort_order'] 	= array('sort_order', 'is_natural');
		$rules['setting'] 		= array('', 'callback__check_setting');
		
		$this->form_validation->set_rules_params($params, $rules);
	}
	
	/**
	 * Kiem tra setting
	 */
	function _check_setting($value)
	{
		$error 	= '';
		$info 	= $this->data['info'];
		$value	= $this->_get_setting($info->key);
		if ( ! $this->module->{$info->key}->setting_check($info, $value, $error))
		{
			$this->form_validation->set_message(__FUNCTION__, $error);
			return FALSE;
		}
		
		return TRUE;
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Lay gia tri cua layout
	 */
	function _get_layout($module)
	{
		$layouts = $this->_get_list_layout();
		$site_methods = $this->module->{$module}->config->item('site_methods');
		$site_methods[] = '_';
		
		$input = $this->input->post('layout');
		$input = array_filter($input);
		foreach ($input as $method => $layout)
		{
			if ( ! in_array($method, $site_methods) || ! isset($layouts[$layout]))
			{
				unset($input[$method]);
			}
		}
		
		return $input;
	}
	
	/**
	 * Lay danh sach layout
	 */
	function _get_list_layout()
	{
		$tpl = t('tpl')->load_config('site');
		
		return array_get($tpl, 'layouts', array());
	}
	
	
	/**
	 * Lay gia tri cua setting
	 */
	function _get_setting($module)
	{
		$input 	= $this->input->post('setting');
		$params = $this->module->{$module}->setting_get_config();
		
		$values = array();
		foreach ($params as $p => $o)
		{
			$v = (isset($input[$p])) ? $input[$p] : '';
			if ($this->module->param_is($o, 'file'))
			{
				$multi = (in_array($o['type'], array('file', 'image'))) ? FALSE : TRUE;
				$v = $this->_get_setting_file($module, $p, $multi);
			}
			
			$values[$p] = $this->module->handle_param_value($o, $v);
		}
		
		return $values;
	}
	
	/**
	 * Lay file name cua param setting dang file 
	 */
	function _get_setting_file($module, $param, $multi)
	{
		$this->load->model('file_model');
		
		if ($multi)
		{
			$file = array();
			$list = $this->file_model->get_list_of_mod('module', $module, 'setting-'.$param, 'file_name');
			foreach ($list as $row)
			{
				$file[] = $row->file_name;
			}
		}
		else 
		{
			$file = $this->file_model->get_info_of_mod('module', $module, 'setting-'.$param, 'file_name');
			$file = ($file) ? $file->file_name : '';
		}
		
		return $file;
	}
	
	/**
	 * Cap nhat gia tri cua field
	 */
	function _update_field_value($module, $field)
	{
		// Lay data
		$data = array();
		
		$match = '';
		if (preg_match('#^setting-(.+)$#i', $field, $match))
		{
			$param 	= $match[1];
			$params = $this->module->{$module}->setting_get_config();
			if (isset($params[$param]))
			{
				if ($this->module->param_is($params[$param], 'file'))
				{
					$data['setting'] = module_get_setting($module);
					
					$multi = (in_array($params[$param]['type'], array('file', 'image'))) ? FALSE : TRUE;
					$data['setting'][$param] = $this->_get_setting_file($module, $param, $multi);
				}
			}
		}
		
		// Cap nhat du lieu vao data
		if (count($data))
		{
			$this->module_model->set($module, $data);
		}
	}
	
	/**
	 * Tu dong kiem tra gia tri cua bien
	 */
	function _autocheck($param)
	{
		$this->_set_rules($param);
		
		$result = array();
		$result['accept'] 	= $this->form_validation->run();
		$result['error'] 	= form_error($param);
		
		$output = json_encode($result);
		set_output('json', $output);
	}
	
	
/*
 * ------------------------------------------------------
 *  Action handle
 * ------------------------------------------------------
 */
	/**
	 * Cai dat
	 */
	function _install($info)
	{
		// Lay input
		$module = $info->key;
		
		// Goi fun install_pre cua module
		$this->module->{$module}->install_pre();
		
		
		// Cap nhat data
		$data = array();
		$data['name'] 	= $this->module->{$module}->config->item('name');
		$data['status'] = config('status_off', 'main');
		$this->module_model->set($module, $data);
		
		// Tao table trong db
		$tables = $this->module->{$module}->table_get_config();
		foreach ($tables as $t => $o)
		{
			$table = (object)$o;
			$table->key = $t;
			$table->db_name = $this->module_model->table_get_db_name($module, $table->key);
			
			$this->_table_create_db($table);
		}
		
		
		// Goi fun install cua module
		$info = (object)$data;
		$info->key = $module;
		$this->module->{$module}->install($info);
		
		// Gui thong bao
		set_message(lang('notice_update_success'));
	}
	
	/**
	 * Go bo
	 */
	function _uninstall($info)
	{
		// Lay input
		$module = $info->key;
		
		// Goi fun uninstall_pre cua module
		$this->module->{$module}->uninstall_pre($info);
		
		
		// Cap nhat data
		$this->module_model->del($module);
		
		// Xoa table trong db
		$tables = $this->module->{$module}->table_get_config();
		foreach ($tables as $t => $o)
		{
			$table = (object)$o;
			$table->key = $t;
			$table->db_name = $this->module_model->table_get_db_name($module, $table->key);
			
			$this->_table_del_db($table);
		}
		
		
		// Goi fun uninstall cua module
		$this->module->{$module}->uninstall($info);
		
		// Gui thong bao
		set_message(lang('notice_update_success'));
	}
	/**
	 * Sua
	 */
	function _edit($info)
	{
		// Lay input
		$module = $info->key;

		// Goi fun setting_pre cua module
		$this->module->{$module}->setting_pre($info);

		// Luu thong tin module
		$this->data['info'] = $info;


		// Tai cac file thanh phan
		$this->load->library('form_validation');
		$this->load->helper('form');

		// Cap nhat gia tri
		if ($this->input->get('act') == 'update')
		{
			$field = $this->input->get('field');
			$this->_update_field_value($module, $field);
			exit();
		}

		// Tu dong kiem tra gia tri cua 1 bien
		$param = $this->input->post('_autocheck');
		if ($param)
		{
			$this->_autocheck($param);
		}

		// Xu ly form
		if ($this->input->post('_submit'))
		{
			// Gan dieu kien cho cac bien
			$params = array('name', 'sort_order', 'layout', 'setting');
			$this->_set_rules($params);

			// Xu ly du lieu
			$result = array();
			if ($this->form_validation->run())
			{
				// Lay input
				$data = array();
				$data['name']			= $this->input->post('name');
				$data['status'] 		= config( ($this->input->post('status')) ? 'status_on' : 'status_off' , 'main');
				$data['sort_order']		= $this->input->post('sort_order');
				//$data['layout']			= $this->_get_layout($module);
				$data['setting']		= $this->_get_setting($module);

				// Goi fun setting_save_pre cua module
				foreach ($data as $p => $v)
				{
					$info->$p = $v;
				}
				$this->module->{$module}->setting_save_pre($info);

				// Cap nhat du lieu vao data
				$data = extend($data, (array)$info);
				$this->module_model->set($info->key, $data);

				// Goi fun setting_save cua module
				$this->module->{$module}->setting_save($info);

				// Khai bao du lieu tra ve
				$result['complete'] = TRUE;
				$result['location'] = admin_url('module');
				set_message(lang('notice_update_success'));
			}
			else
			{
				foreach ($params as $param)
				{
					$result[$param] = form_error($param);
				}
			}

			// Form output
			$this->_form_submit_output($result);
		}


		// Goi fun setting cua module
		$this->module->{$module}->setting($info);

		// Lay danh sach cac bien setting
		$setting_params = $this->module->{$module}->setting_get_config();
		foreach ($setting_params as $p => $o)
		{
			// Khai bao cac bien cua widget upload
			if ($this->module->param_is($o, 'file'))
			{
				$_upload = array();
				$_upload['mod'] 			= (in_array($o['type'], array('file', 'image'))) ? 'single' : 'multi';
				$_upload['file_type'] 		= (in_array($o['type'], array('file', 'file_multi'))) ? 'file' : 'image';
				$_upload['allowed_types'] 	= $o['file_allowed'];
				$_upload['status'] 			= config( ($o['file_private']) ? 'file_private' : 'file_public' , 'main');
				$_upload['server'] 			= $o['file_server'];
				$_upload['table'] 			= 'module';
				$_upload['table_id'] 		= $info->key;
				$_upload['table_field'] 	= 'setting-'.$p;
				$_upload['resize'] 			= FALSE;
				$_upload['thumb'] 			= $o['file_thumb'];
				$_upload['sort'] 			= TRUE;
				$_upload['url_update']		= current_url().'?act=update&field='.$_upload['table_field'];

				$o['_upload'] = $_upload;
			}

			$setting_params[$p] = $o;
		}
		$this->data['setting_params'] = $setting_params;


		if (empty($setting_params))
		{
			$_table = $this->module->{$module}->table_get_config();

			if ( ! empty($_table))
			{
				$table = head(array_keys($_table));

				redirect_admin("md-{$module}/{$table}/list");
			}
		}


		// Luu cac bien gui den view
		$this->data['action'] 	= current_url();
		$this->data['info'] 	= $info;
		$this->data['layouts'] 	= $this->_get_list_layout();
		$this->data['site_methods'] = $this->module->{$module}->config->item('site_methods');

		// Tao cay thu muc
		/*$breadcrumbs = array();
		$breadcrumbs[] = array(admin_url('module'), lang('mod_module'));
		$breadcrumbs[] = array(current_url(), lang('setting'));
		$this->data['breadcrumbs'] = $breadcrumbs;*/

		// Hien thi view
		$this->_display();
	}

	/**
	 * Cai dat
	 */
	function _setting($info)
	{
		// Lay input
		$module = $info->key;
		
		// Goi fun setting_pre cua module
		$this->module->{$module}->setting_pre($info);
		
		// Luu thong tin module
		$this->data['info'] = $info;
		
		
		// Tai cac file thanh phan
		$this->load->library('form_validation');
		$this->load->helper('form');
		
		// Cap nhat gia tri
		if ($this->input->get('act') == 'update')
		{
			$field = $this->input->get('field');
			$this->_update_field_value($module, $field);
			exit();
		}
		
		// Tu dong kiem tra gia tri cua 1 bien
		$param = $this->input->post('_autocheck');
		if ($param)
		{
			$this->_autocheck($param);
		}
		
		// Xu ly form
		if ($this->input->post('_submit'))
		{
			// Gan dieu kien cho cac bien
			$params = array('setting');
			$this->_set_rules($params);
			
			// Xu ly du lieu
			$result = array();
			if ($this->form_validation->run())
			{
				// Lay input
				$data = array();
				$data['setting']		= $this->_get_setting($module);
				
				// Goi fun setting_save_pre cua module
				foreach ($data as $p => $v)
				{
					$info->$p = $v;
				}
				$this->module->{$module}->setting_save_pre($info);
				
				// Cap nhat du lieu vao data
				$data = extend($data, (array)$info);
				$this->module_model->set($info->key, $data);
				
				// Goi fun setting_save cua module
				$this->module->{$module}->setting_save($info);
				
				// Khai bao du lieu tra ve
				$result['complete'] = TRUE;
				$result['location'] = current_url();
				set_message(lang('notice_update_success'));
			}
			else
			{
				foreach ($params as $param)
				{
					$result[$param] = form_error($param);
				}
			}
			
			// Form output
			$this->_form_submit_output($result);
		}
		
		
		// Goi fun setting cua module
		$this->module->{$module}->setting($info);
		
		// Lay danh sach cac bien setting
		$setting_params = $this->module->{$module}->setting_get_config();
		foreach ($setting_params as $p => $o)
		{
			// Khai bao cac bien cua widget upload
			if ($this->module->param_is($o, 'file'))
			{
				$_upload = array();
				$_upload['mod'] 			= (in_array($o['type'], array('file', 'image'))) ? 'single' : 'multi';
				$_upload['file_type'] 		= (in_array($o['type'], array('file', 'file_multi'))) ? 'file' : 'image';
				$_upload['allowed_types'] 	= $o['file_allowed'];
				$_upload['status'] 			= config( ($o['file_private']) ? 'file_private' : 'file_public' , 'main');
				$_upload['server'] 			= $o['file_server'];
				$_upload['table'] 			= 'module';
				$_upload['table_id'] 		= $info->key;
				$_upload['table_field'] 	= 'setting-'.$p;
				$_upload['resize'] 			= FALSE;
				$_upload['thumb'] 			= $o['file_thumb'];
				$_upload['sort'] 			= TRUE;
				$_upload['url_update']		= current_url().'?act=update&field='.$_upload['table_field'];
				
				$o['_upload'] = $_upload;
			}
			
			$setting_params[$p] = $o;
		}
		$this->data['setting_params'] = $setting_params;
		
		
		if (empty($setting_params))
		{
			$_table = $this->module->{$module}->table_get_config();
			
			if ( ! empty($_table))
			{
				$table = head(array_keys($_table));
				
				redirect_admin("md-{$module}/{$table}/list");
			}
		}
		
		
		// Luu cac bien gui den view
		$this->data['action'] 	= current_url();
		$this->data['info'] 	= $info;
		$this->data['layouts'] 	= $this->_get_list_layout();
		$this->data['site_methods'] = $this->module->{$module}->config->item('site_methods');
		
		// Tao cay thu muc
		$breadcrumbs = array();
		$breadcrumbs[] = array(admin_url('module'), lang('mod_module'));
		$breadcrumbs[] = array(current_url(), lang('setting'));
		$this->data['breadcrumbs'] = $breadcrumbs;
		
		// Hien thi view
		$this->_display();
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Thuc hien tuy chinh
	 */
	function _action($action)
	{
		// Lay input
		$ids = $this->uri->rsegment(3);
		$ids = ( ! $ids) ? $this->input->post('id') : $ids;
		$ids = ( ! is_array($ids)) ? array($ids) : $ids;
		
		// Thuc hien action
		foreach ($ids as $id)
		{
			// Lay thong tin
			$info = FALSE;
			if ($action == 'install')
			{
				$info = new stdClass();
				$info->key 	= $id;
			}
			else
			{
				$info = $this->module_model->get($id);
			}
			
			// Neu khong ton tai
			if ( ! $info) continue;
			
			// Kiem tra co the thuc hien hanh dong nay khong
			if ( ! $this->_can_do($info, $action)) continue;
			
			// Chuyen den ham duoc yeu cau
			$this->{'_'.$action}($info);
		}
	}
	
	/**
	 * Kiem tra co the thuc hien hanh dong hay khong
	 */
	function _can_do($info, $action)
	{
		switch ($action)
		{
			case 'install':
			{
				return (module_exists($info->key) && ! module_install($info->key)) ? TRUE : FALSE;
			}
			
			case 'uninstall':
			case 'edit':
			case 'setting':
			{
				return (module_install($info->key)) ? TRUE : FALSE;
			}
		}
		
		return FALSE;
	}
	
	
/*
 * ------------------------------------------------------
 *  Table handle
 * ------------------------------------------------------
 */
	/**
	 * List cac row cua table
	 */
	function _table($table)
	{
		// Tai file thanh phan
		$this->load->model('db_model');
		
		// Sort
		if ($this->input->get('act') == 'sort_update')
		{
			$this->_table_sort_update($table);
		}
		
		// Lay cac row cua table
		$order = $this->_table_can_do($table, 'sort')
					? array('sort_order', 'asc')
					: array();
		
		$rows = $this->db_model->get($table->db_name, $order);
		$actions = array('edit', 'del');
		foreach ($rows as $row)
		{
			foreach ($actions as $action)
			{
				$row->{'_url_'.$action}	= module_url( 'admin',$table->module->key, $table->key.'/'.$action.'/'.$row->_id);
				$row->{'_can_'.$action} = ($this->_row_can_do($row, $action) && admin_permission_url($row->{'_url_'.$action})) ? TRUE : FALSE;
			}

			$row->_id_full = $table->id.':'.$row->_id;
		}
		// Tao action list
		$actions = array();
		foreach (array('del') as $v)
		{
			$url = module_url( 'admin',$table->module->key, $table->key.'/'.$v);
			if ( ! admin_permission_url($url)) continue;
			
			$actions[$v] = $url;
		}
		$this->data['actions'] = $actions;
		
		
		// Luu cac bien gui den view
		$this->data['action'] 	= current_url();
		$this->data['table'] 	= $table;
		$this->data['rows'] 	= $rows;
		$this->data['sort'] 	= $this->_table_can_do($table, 'sort');
		$this->data['sort_url_update'] = current_url().'?act=sort_update';
		
		// Tao cay thu muc
		$breadcrumbs = array();
		$breadcrumbs[] = array('', lang('mod_module'));
		$breadcrumbs[] = array(current_url(), $table->module->name);
		$breadcrumbs[] = array(current_url(), $table->name);
		$this->data['breadcrumbs'] = $breadcrumbs;
		
		// Hien thi view
		$this->_display();
	}
	
	/**
	 * Update sort_order
	 * 
	 * @param object $table
	 */
	protected function _table_sort_update($table)
	{
		if ( ! $this->_table_can_do($table, 'sort')) return;
		
		$items = $this->input->post('items');
		$items = explode(',', $items);
		
		foreach ($items as $i => $id)
		{
			$sort_order = $i + 1;
		
			model('db')->update($table->db_name, $id, compact('sort_order'));
		}
			
		$output = json_encode(array('complete' => TRUE));
		set_output('json', $output);
	}
	
	/**
	 * Cap nhat thong tin table tu config
	 */
	function _table_update($table)
	{
		// Tai file thanh phan
		$this->load->helper('file');
		$this->load->model('db_model');
		
		// Xoa nhung col khong co trong config
		$rows = NULL;
		$db_cols = $this->db_model->get_list_col($table->db_name);
		foreach ($db_cols as $c => $c_i)
		{
			// Neu col co trong config
			if (isset($table->cols[$c])) continue;
			
			// Xoa file cua col trong row
			$rows = ($rows === NULL) ? $this->db_model->get($table->db_name) : $rows;
			foreach ($rows as $row)
			{
				file_del_table($table->db_name, $row->_id, $c);
			}
			
			// Xoa col trong db
			$this->db_model->del_col($table->db_name, $c);
		}
		
		// Cap nhat thong tin table
		$this->_table_create_db($table);
		
		// Chuyen den trang list cua table
		set_message(lang('notice_update_success'));
		redirect( module_url( 'admin',$table->module->key, $table->key.'/list') );
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Thuc hien tuy chinh
	 */
	function _table_action($action)
	{
		// Lay input
		$ids = $this->uri->rsegment(3);
		$ids = ( ! $ids) ? $this->input->post('id') : $ids;
		$ids = ( ! is_array($ids)) ? array($ids) : $ids;
		
		// Thuc hien action
		foreach ($ids as $id)
		{
			// Kiem tra module
			$table = $this->_table_get_info($id);
			if ( ! $table) continue;
			
			// Kiem tra co the thuc hien hanh dong nay khong
			if ( ! $this->_table_can_do($table, $action)) continue;
			
			// Chuyen den ham duoc yeu cau
			$this->{'_'.$action}($table);
		}
	}
	
	/**
	 * Kiem tra co the thuc hien hanh dong hay khong
	 */
	function _table_can_do($table, $action)
	{
		switch ($action)
		{
			case 'table':
			case 'table_update':
			case 'row_add':
			{
				return TRUE;
			}
			case 'sort':
			{
				return isset($table->cols['sort_order']);
			}
		}
		
		return FALSE;
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Lay thong tin table
	 */
	function _table_get_info($table_id)
	{
		// Xu ly id
		$id = explode(':', $table_id, 2);
		if (count($id) != 2)
		{
			return FALSE;
		}
		
		// Kiem tra module
		$module = $id[0];
		$module = $this->module_model->get($module);
		if ( ! $module)
		{
			return FALSE;
		}
		
		// Kiem tra table
		$table 	= $id[1];
		$tables = $this->module->{$module->key}->table_get_config();
		if ( ! isset($tables[$table]))
		{
			return FALSE;
		}
		
		// Lay thong tin table
		$table = (object)$tables[$table];
		$table->id		= $table_id;
		$table->key 	= $id[1];
		$table->module	= $module;
		$table->db_name = $this->module_model->table_get_db_name($module->key, $table->key);
		
		return $table;
	}
	
	/**
	 * Tao table trong db
	 */
	function _table_create_db($table)
	{
		// Tai file thanh phan
		$this->load->model('db_model');
		
		// Tao table
		$this->db_model->create_table($table->db_name);
		
		// Tao cols
		foreach ($table->cols as $c => $o)
		{
			$type = (in_array($o['type'], array('select_multi', 'checkbox', 'file_multi', 'image_multi'))) ? 'list' : 'text';
			
			$data = array();
			$data['name']	= $c;
			$data['type']	= array_search($type, $this->db_model->_types);
			$this->db_model->create_col($table->db_name, $data);
		}
	}
	
	/**
	 * Xoa table trong db
	 */
	function _table_del_db($table)
	{
		// Tai file thanh phan
		$this->load->helper('file');
		$this->load->model('db_model');
		
		// Xoa cac file cua row
		foreach ($table->cols as $c => $o)
		{
			if ($this->module->param_is($o, 'file'))
			{
				$rows = $this->db_model->get($table->db_name);
				foreach ($rows as $row)
				{
					file_del_table($table->db_name, $row->_id);
				}
				
				break;
			}
		}
		
		// Xoa table
		$this->db_model->del_table($table->db_name);
	}
	
	
/*
 * ------------------------------------------------------
 *  Table Row handle
 * ------------------------------------------------------
 */
	/**
	 * Gan dieu kien cho cac bien
	 */
	function _row_set_rules($cols)
	{
		$params = array();
		foreach ($cols as $c => $o)
		{
			$params[] = $c;
			$this->form_validation->set_rules($c, $o['name'], 'xss_clean');
		}
		
		return $params;
	}
	
	/**
	 * Lay gia tri cua setting
	 */
	function _row_get_values($table, $row_id)
	{
		$values = array();
		foreach ($table->cols as $c => $o)
		{
			$v = $this->input->post($c);
			if ($this->module->param_is($o, 'file'))
			{
				$multi = (in_array($o['type'], array('file', 'image'))) ? FALSE : TRUE;
				$v = $this->_row_get_file($table, $c, $row_id, $multi);
			}
			
			$values[$c] = $this->module->handle_param_value($o, $v);
		}
		
		return $values;
	}
	
	/**
	 * Lay file name cua column dang file 
	 */
	function _row_get_file($table, $col, $row_id, $multi)
	{
		$this->load->model('file_model');
		
		if ($multi)
		{
			$file = array();
			$list = $this->file_model->get_list_of_mod($table->db_name, $row_id, $col, 'file_name');
			foreach ($list as $row)
			{
				$file[] = $row->file_name;
			}
		}
		else 
		{
			$file = $this->file_model->get_info_of_mod($table->db_name, $row_id, $col, 'file_name');
			$file = ($file) ? $file->file_name : '';
		}
		
		return $file;
	}
	
	/**
	 * Cap nhat gia tri cua row
	 */
	function _row_update_value($table, $col, $row_id)
	{
		// Lay data
		$data = array();
		if (isset($table->cols[$col]))
		{
			if ($this->module->param_is($table->cols[$col], 'file'))
			{
				$multi = (in_array($table->cols[$col]['type'], array('file', 'image'))) ? FALSE : TRUE;
				$data[$col] = $this->_row_get_file($table, $col, $row_id, $multi);
			}
		}
		
		// Cap nhat du lieu vao data
		if (count($data))
		{
			$this->db_model->update($table->db_name, $row_id, $data);
		}
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Them moi row
	 */
	function _row_add($table)
	{
		// Tai file thanh phan
		$this->load->model('db_model');
		
		// Tao fake id
		$fake_id = fake_id_get($table->db_name);
		
		// Tai cac file thanh phan
		$this->load->helper('form');
		$this->load->library('form_validation');
		
		// Xu ly form
		if ($this->input->post('_submit'))
		{
			// Gan dieu kien cho cac bien
			$params = $this->_row_set_rules($table->cols);
			
			// Xu ly du lieu
			$result = array();
			if ($this->form_validation->run())
			{
				// Them du lieu vao data
				$data 	= $this->_row_get_values($table, $fake_id);
				$row_id = $this->db_model->insert($table->db_name, $data);
				
				// Cap nhat lai table_id trong table file
				$this->load->model('file_model');
				$this->file_model->update_table_id_of_mod($table->db_name, $fake_id, $row_id);
				
				// Xoa fake_id
				fake_id_del($table->db_name);
				
				// Khai bao du lieu tra ve
				$result['complete'] = TRUE;
				$result['location'] = module_url('admin', $table->module->key, $table->key.'/list');
				set_message(lang('notice_add_success'));
			}
			else
			{
				foreach ($params as $param)
				{
					$result[$param] = form_error($param);
				}
			}
			
			// Form output
			$this->_form_submit_output($result);
		}
		
		
		// Khai bao cac bien cua widget upload
		foreach ($table->cols as $c => $o)
		{
			// Khai bao cac bien cua widget upload
			if ($this->module->param_is($o, 'file'))
			{
				$_upload = array();
				$_upload['mod'] 			= (in_array($o['type'], array('file', 'image'))) ? 'single' : 'multi';
				$_upload['file_type'] 		= (in_array($o['type'], array('file', 'file_multi'))) ? 'file' : 'image';
				$_upload['allowed_types'] 	= $o['file_allowed'];
				$_upload['status'] 			= config( ($o['file_private']) ? 'file_private' : 'file_public' , 'main');
				$_upload['server'] 			= $o['file_server'];
				$_upload['table'] 			= $table->db_name;
				$_upload['table_id'] 		= $fake_id;
				$_upload['table_field'] 	= $c;
				$_upload['resize'] 			= FALSE;
				$_upload['thumb'] 			= $o['file_thumb'];
				
				$o['_upload'] = $_upload;
			}
			
			$table->cols[$c] = $o;
		}
		
		// Luu cac bien gui den view
		$this->data['action'] 	= current_url();
		$this->data['table'] 	= $table;
		
		// Tao cay thu muc
		$breadcrumbs = array();
		$breadcrumbs[] = array('', lang('mod_module'));
		$breadcrumbs[] = array('', $table->module->name);
		$breadcrumbs[] = array(admin_url('module/table/'.$table->id), $table->name);
		$breadcrumbs[] = array(current_url(), lang('title_row_add'));
		$this->data['breadcrumbs'] = $breadcrumbs;
		
		// Hien thi view
		$this->_display();
	}
	
	/**
	 * Chinh sua
	 */
	function _row_edit($table, $row)
	{
		// Tai cac file thanh phan
		$this->load->helper('form');
		$this->load->library('form_validation');
		
		// Cap nhat gia tri
		if ($this->input->get('act') == 'update')
		{
			$col = $this->input->get('col');
			$this->_row_update_value($table, $col, $row->_id);
			exit();
		}
		
		// Xu ly form
		if ($this->input->post('_submit'))
		{
			// Gan dieu kien cho cac bien
			$params = $this->_row_set_rules($table->cols);
			
			// Xu ly du lieu
			$result = array();
			if ($this->form_validation->run())
			{
				// Cap nhat du lieu vao data
				$data = $this->_row_get_values($table, $row->_id);
				$this->db_model->update($table->db_name, $row->_id, $data);
				
				// Khai bao du lieu tra ve
				$result['complete'] = TRUE;
				$result['location'] = module_url('admin', $table->module->key, $table->key.'/list');
				set_message(lang('notice_update_success'));
			}
			else
			{
				foreach ($params as $param)
				{
					$result[$param] = form_error($param);
				}
			}
			
			// Form output
			$this->_form_submit_output($result);
		}
		
		
		// Khai bao cac bien cua widget upload
		foreach ($table->cols as $c => $o)
		{
			// Khai bao cac bien cua widget upload
			if ($this->module->param_is($o, 'file'))
			{
				$_upload = array();
				$_upload['mod'] 			= (in_array($o['type'], array('file', 'image'))) ? 'single' : 'multi';
				$_upload['file_type'] 		= (in_array($o['type'], array('file', 'file_multi'))) ? 'file' : 'image';
				$_upload['allowed_types'] 	= $o['file_allowed'];
				$_upload['status'] 			= config( ($o['file_private']) ? 'file_private' : 'file_public' , 'main');
				$_upload['server'] 			= $o['file_server'];
				$_upload['table'] 			= $table->db_name;
				$_upload['table_id'] 		= $row->_id;
				$_upload['table_field'] 	= $c;
				$_upload['resize'] 			= FALSE;
				$_upload['thumb'] 			= $o['file_thumb'];
				$_upload['url_update']		= current_url().'?act=update&col='.$_upload['table_field'];
				
				$o['_upload'] = $_upload;
			}
			
			$table->cols[$c] = $o;
		}
		
		// Luu cac bien gui den view
		$this->data['action'] 	= current_url();
		$this->data['table'] 	= $table;
		$this->data['row'] 		= $row;
		
		// Tao cay thu muc
		$breadcrumbs = array();
		$breadcrumbs[] = array('', lang('mod_module'));
		$breadcrumbs[] = array('', $table->module->name);
		$breadcrumbs[] = array(admin_url('module/table/'.$table->id), $table->name);
		$breadcrumbs[] = array(current_url(), lang('title_row_edit'));
		$this->data['breadcrumbs'] = $breadcrumbs;
		
		// Hien thi view
		$this->_display();
	}
	
	/**
	 * Xoa du lieu
	 */
	function _row_del($table, $row)
	{
		// Thuc hien xoa
		$this->db_model->del($table->db_name, $row->_id);
		
		// Xoa file
		$this->load->helper('file');
		file_del_table($table->db_name, $row->_id);
		
		// Gui thong bao
		set_message(lang('notice_del_success'));
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Thuc hien tuy chinh
	 */
	function _row_action($action)
	{
		// Tai file thanh phan
		$this->load->model('db_model');
		
		// Lay input
		$ids = $this->uri->rsegment(3);
		$ids = ( ! $ids) ? $this->input->post('id') : $ids;
		$ids = ( ! is_array($ids)) ? array($ids) : $ids;
		
		// Thuc hien action
		foreach ($ids as $id)
		{
			// Xu ly id
			$id = explode(':', $id);
			if (count($id) < 2) continue;
			
			// Lay id
			$_id 		= array_pop($id);
			$table_id 	= implode(':', $id);
			
			// Kiem tra table_id
			$table = $this->_table_get_info($table_id);
			if ( ! $table) continue;
			
			// Kiem tra _id
			$row = $this->db_model->row($table->db_name, $_id);
			if ( ! $row) continue;
			
			// Kiem tra co the thuc hien hanh dong nay khong
			if ( ! $this->_row_can_do($row, $action)) continue;
			
			// Chuyen den ham duoc yeu cau
			$this->{'_'.$action}($table, $row);
		}
	}
	
	/**
	 * Kiem tra co the thuc hien hanh dong hay khong
	 */
	function _row_can_do($info, $action)
	{
		switch ($action)
		{
			case 'edit':
			case 'del':
			case 'row_edit':
			case 'row_del':
			{
				return TRUE;
			}
		}
		
		return FALSE;
	}
	
}