<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Widget extends MY_Controller {
	
	/**
	 * Ham khoi dong
	 */
	function __construct()
	{
		parent::__construct();
		
		// Tai cac file thanh phan
		$this->load->model('widget_model');
		$this->lang->load('admin/widget');
	}
	
	/**
	 * Remap method
	 */
	function _remap($method)
	{
		if (in_array($method, array('edit', 'del','on','off')))
		{
			$this->_action($method);
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
		$rules['region'] 		= array('region', 'required|callback__check_region');
		//$rules['layout'] 		= array('region', 'required|callback__check_layout');
		$rules['sort_order'] 	= array('sort_order', 'is_natural');
		$rules['url_show'] 		= array('url_show', 'trim|xss_clean');
		$rules['url_hide'] 		= array('url_hide', 'trim|xss_clean');
		$rules['setting'] 		= array('', 'callback__check_setting');

		$this->form_validation->set_rules_params($params, $rules);
	}

	/**
	 * Kiem tra region
	 */
	function _check_region($value)
	{
		$regions = $this->_get_regions();
		if ( ! isset($regions[$value]))
		{
			$this->form_validation->set_message(__FUNCTION__, lang('notice_value_not_exist'));
			return FALSE;
		}

		return TRUE;
	}

	/**
	 * Kiem tra layout
	 */
	function _check_layout($value)
	{

		$layouts = $this->_get_layouts();
		if ( ! isset($layouts[$value]))
		{
			$this->form_validation->set_message(__FUNCTION__, lang('notice_value_not_exist'));
			return FALSE;
		}

		return TRUE;
	}
	/**
	 * Kiem tra setting
	 */
	function _check_setting($value)
	{
		$error 	= '';
		$info 	= $this->data['info'];
		$value	= $this->_get_setting($info);
		if ( ! $this->module->{$info->module}->widget_setting_check($info, $value, $error))
		{
			$this->form_validation->set_message(__FUNCTION__, $error);
			return FALSE;
		}

		return TRUE;
	}

	// --------------------------------------------------------------------

	/**
	 * Lay danh sach regions cua template
	 */
	function _get_regions()
	{
		$tpl = t('tpl')->load_config('site');

		return array_get($tpl, 'regions', array());
	}
	/**
	 * Lay danh sach layouts cua template
	 */
	function _get_layouts()
	{
		$tpl = t('tpl')->load_config('site');
		return array_get($tpl, 'layouts', array());
	}

	/**
	 * Lay gia tri cua setting
	 */
	function _get_setting($widget)
	{
		$input 	= $this->input->post('setting', false);

		$params = $this->module->{$widget->module}->widget_get_config();
		$params = $params[$widget->widget]['setting'];

		$values = array();
		foreach ($params as $p => $o)
		{
			$v = (isset($input[$p])) ? $input[$p] : '';
			if ($this->module->param_is($o, 'file'))
			{
				$multi = (in_array($o['type'], array('file', 'image'))) ? FALSE : TRUE;
				$v = $this->_get_setting_file($widget->id, $p, $multi);
			}

			$values[$p] = $this->module->handle_param_value($o, $v);
		}

		return $values;
	}

	/**
	 * Lay file name cua param setting dang file
	 */
	function _get_setting_file($widget_id, $param, $multi)
	{
		$this->load->model('file_model');

		if ($multi)
		{
			$file = array();
			$list = $this->file_model->get_list_of_mod('widget', $widget_id, 'setting-'.$param, 'file_name');
			foreach ($list as $row)
			{
				$file[] = $row->file_name;
			}
		}
		else
		{
			$file = $this->file_model->get_info_of_mod('widget', $widget_id, 'setting-'.$param, 'file_name');
			$file = ($file) ? $file->file_name : '';
		}

		return $file;
	}

	/**
	 * Lay gia tri cua url_show, url_hide
	 */
	function _get_url_value($param)
	{
		$input = $this->input->post($param);
		$input = explode("\n", $input);

		$list = array();
		foreach ($input as $v)
		{
			$v = trim($v);
			if ( ! $v) continue;

			$list[] = $v;
		}
		$list = array_unique($list);

		return $list;
	}

	/**
	 * Cap nhat gia tri cua field
	 */
	function _update_field_value($widget, $field)
	{
		// Lay data
		$data = array();

		$match = '';
		if (preg_match('#^setting-(.+)$#i', $field, $match))
		{
			$param 	= $match[1];

			$params = $this->module->{$widget->module}->widget_get_config();
			$params = $params[$widget->widget]['setting'];

			if (isset($params[$param]))
			{
				if ($this->module->param_is($params[$param], 'file'))
				{
					$data['setting'] = $this->widget_model->setting_get($widget->id);

					$multi = (in_array($params[$param]['type'], array('file', 'image'))) ? FALSE : TRUE;
					$data['setting'][$param] = $this->_get_setting_file($widget->id, $param, $multi);
				}
			}
		}

		// Cap nhat du lieu vao data
		if (count($data))
		{
			$data = $this->widget_model->handle_data_input($data);
			$this->widget_model->update($widget->id, $data);
		}
	}

	/**
	 * Lay danh sach module ho tro widget
	 */
	function _get_list_module()
	{
		$this->load->model('module_model');
		$modules_install = $this->module_model->get_list();
		$modules = array();
		foreach ($modules_install as $row)
		{
			if ( ! $this->module->{$row->key}->config->item('widget'))
			{
				continue;
			}
			$widget = $this->module->{$row->key}->widget_get_config();
			foreach ($widget as $w => $w_o)
			{
				unset($widget[$w]['setting']);
			}

			$row->widget = $widget;
			$modules[$row->key] = $row;
		}
		return $modules;
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
	 * Lua chon module
	 */
	function add_select_module()
	{
		// Xu ly form
		if ($this->input->post('_submit'))
		{
			$module= $this->input->post('module');
			if($module){
				$result['complete'] = TRUE;
				$result['location'] =admin_url('widget/add'."?module=".$module);
				// Form output
				$this->_form_submit_output($result);
			}

			return;
		}
		// Luu cac bien gui den view
		$this->data['action'] 	= admin_url('widget/add');
		$this->data['modules'] 	= $this->_get_list_module();

		// Tao cay thu muc
		$breadcrumbs = array();
		$breadcrumbs[] = array(admin_url('widget'), lang('mod_widget'));
		$breadcrumbs[] = array(current_url(), lang('add'));
		$this->data['breadcrumbs'] = $breadcrumbs;

		// Hien thi view
		$this->_display();
	}

	/**
	 * Them moi
	 */
	function add()
	{
		// Lay input
		$module = $this->input->get('module');
		$module = explode(':', $module, 2);
		$widget = (isset($module[1])) ? $module[1] : '';
		$module = $module[0];

		// Kiem tra module
		if ( ! module_install($module))
		{
			redirect_admin('widget/add_select_module');
		}

		// Kiem tra widget
		$widget_config = $this->module->{$module}->widget_get_config();
		if ( ! isset($widget_config[$widget]))
		{
			redirect_admin('widget/add_select_module');
		}


		// Tao thong tin mac dinh cua widget
		$fake_id = fake_id_get('widget');

		$info = new stdClass();
		$info->id 		= $fake_id;
		$info->name 	= $widget_config[$widget]['name'];
		$info->module 	= $module;
		$info->widget 	= $widget;
		$info->setting 	= array();

		// Goi fun widget_setting_pre cua module
		$this->module->{$module}->widget_setting_pre($info);

		// Luu thong tin widget
		$this->data['info'] = $info;


		// Tai cac file thanh phan
		$this->load->helper('form');
		$this->load->library('form_validation');

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
			$params = array('name', 'region','layout', 'sort_order', 'url_show', 'url_hide', 'setting');
			$this->_set_rules($params);

			// Xu ly du lieu
			$result = array();
			if ($this->form_validation->run())
			{
				// Lay input
				$data = array();
				$data['name']			= $this->input->post('name');
				$data['module']			= $module;
				$data['widget']			= $widget;
				$data['region']			= $this->input->post('region');
				//$data['layout']			= $this->input->post('layout');
				$data['status'] 		= config( ($this->input->post('status')) ? 'status_on' : 'status_off' , 'main');
				$data['sort_order']		= $this->input->post('sort_order');
				$data['status_auth']	= $this->input->post('status_auth');
				$data['url_show']		= $this->_get_url_value('url_show');
				$data['url_hide']		= $this->_get_url_value('url_hide');
				$data['setting']		= $this->_get_setting($info);

				// Goi fun widget_setting_save_pre cua module
				foreach ($data as $p => $v)
				{
					$info->$p = $v;
				}
				$this->module->{$module}->widget_setting_save_pre($info);

				// Cap nhat du lieu vao data
				$data = extend($data, (array)$info);
				$data = $this->widget_model->handle_data_input($data);
				$id = 0;
				$this->widget_model->create($data, $id);

				// Cap nhat lai table_id trong table file
				$this->load->model('file_model');
				$this->file_model->update_table_id_of_mod('widget', $fake_id, $id);

				// Xoa fake_id
				fake_id_del('widget');

				// Goi fun widget_setting_save cua module
				$info->id = $id;
				$this->module->{$module}->widget_setting_save($info);

				// Khai bao du lieu tra ve
				$result['complete'] = TRUE;
				$result['location'] = $this->_url().'?region='.$this->input->post('region');
				set_message(lang('notice_add_success'));
			}

			// Neu du lieu khong phu hop
			if (empty($result['complete']))
			{
				foreach ($params as $param)
				{
					$result[$param] = form_error($param);
				}
			}

			// Form output
			$this->_form_submit_output($result);
		}


		// Goi fun widget_setting cua module
		$this->module->{$module}->widget_setting($info);

		// Lay danh sach cac bien setting
		$setting_params = $widget_config[$widget]['setting'];
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
				$_upload['table'] 			= 'widget';
				$_upload['table_id'] 		= $info->id;
				$_upload['table_field'] 	= 'setting-'.$p;
				$_upload['resize'] 			= FALSE;
				$_upload['thumb'] 			= $o['file_thumb'];

				$o['_upload'] = $_upload;
			}

			$setting_params[$p] = $o;
		}
		$this->data['setting_params'] = $setting_params;

		// Luu cac bien gui den view
		$this->data['action'] 	= current_url(TRUE);
		$this->data['regions'] 	= $this->_get_regions();
		$this->data['layouts'] 	= $this->_get_layouts();

		// Tao cay thu muc
		$breadcrumbs = array();
		$breadcrumbs[] = array(admin_url('widget'), lang('mod_widget'));
		$breadcrumbs[] = array(current_url(), lang('add'));
		$this->data['breadcrumbs'] = $breadcrumbs;

		// Hien thi view
		$this->_display('form');
	}

	/**
	 * Chinh sua
	 */
	function _edit($info)
	{
		// Lay input
		$module = $info->module;

		// Goi fun widget_setting_pre cua module
		$this->module->{$module}->widget_setting_pre($info);

		// Luu thong tin widget
		$this->data['info'] = $info;


		// Tai cac file thanh phan
		$this->load->library('form_validation');
		$this->load->helper('form');

		// Cap nhat gia tri
		if ($this->input->get('act') == 'update')
		{
			$field = $this->input->get('field');
			$this->_update_field_value($info, $field);
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
			$params = array('name', 'region','layout', 'sort_order', 'url_show', 'url_hide', 'setting');
			$this->_set_rules($params);

			// Xu ly du lieu
			$result = array();
			if ($this->form_validation->run())
			{
				// Lay input
				$data = array();
				$data['name']			= $this->input->post('name');
				$data['region']			= $this->input->post('region');
				//$data['layout']		= $this->input->post('layout');
				$data['status'] 		= config( ($this->input->post('status')) ? 'status_on' : 'status_off' , 'main');
				$data['sort_order']		= $this->input->post('sort_order');
				$data['status_auth']	= $this->input->post('status_auth');
				$data['url_show']		= $this->_get_url_value('url_show');
				$data['url_hide']		= $this->_get_url_value('url_hide');
				$data['setting']		= $this->_get_setting($info);

				// Goi fun widget_setting_save_pre cua module
				foreach ($data as $p => $v)
				{
					$info->$p = $v;
				}
				$this->module->{$module}->widget_setting_save_pre($info);

				// Cap nhat du lieu vao data
				$data = extend($data, (array)$info);
				$data = $this->widget_model->handle_data_input($data);
				$this->widget_model->update($info->id, $data);

				// Goi fun widget_setting_save cua module
				$this->module->{$module}->widget_setting_save($info);

				// Khai bao du lieu tra ve
				$result['complete'] = TRUE;
				$result['location'] = $this->_url().'?region='.$this->input->post('region');
				set_message(lang('notice_update_success'));
			}

			// Neu du lieu khong phu hop
			if (empty($result['complete']))
			{
				foreach ($params as $param)
				{
					$result[$param] = form_error($param);
				}
			}

			// Form output
			$this->_form_submit_output($result);
		}


		// Goi fun widget_setting cua module
		$this->module->{$module}->widget_setting($info);

		// Lay danh sach cac bien setting
		$setting_params = $this->module->{$module}->widget_get_config();
		$setting_params = $setting_params[$info->widget]['setting'];
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
				$_upload['table'] 			= 'widget';
				$_upload['table_id'] 		= $info->id;
				$_upload['table_field'] 	= 'setting-'.$p;
				$_upload['resize'] 			= FALSE;
				$_upload['thumb'] 			= $o['file_thumb'];
				$_upload['url_update']		= current_url().'?act=update&field='.$_upload['table_field'];

				$o['_upload'] = $_upload;
			}

			$setting_params[$p] = $o;
		}
		$this->data['setting_params'] = $setting_params;

		// Luu cac bien gui den view
		$this->data['action'] 	= current_url();
		$this->data['info'] 	= $info;
		$this->data['regions'] 	= $this->_get_regions();
		$this->data['layouts'] 	= $this->_get_layouts();

		// Tao cay thu muc
		$breadcrumbs = array();
		$breadcrumbs[] = array(admin_url('widget'), lang('mod_widget'));
		$breadcrumbs[] = array(current_url(), lang('edit'));
		$this->data['breadcrumbs'] = $breadcrumbs;

		// Hien thi view
		$this->_display('form');
	}

	/**
	 * Xoa du lieu
	 */
	function _del($info)
	{
		// Thuc hien xoa
		$this->widget_model->del($info->id);

		// Xoa file
		$this->load->helper('file');
		file_del_table('widget', $info->id);

		// Gui thong bao
		set_message(lang('notice_del_success'));
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
			// Xu ly id
			$id = ( ! is_numeric($id)) ? 0 : $id;

			// Kiem tra id
			$info = $this->widget_model->get_info($id);
			if ( ! $info) continue;

			// Kiem tra co the thuc hien hanh dong nay khong
			$info = (object)$this->widget_model->handle_data_output((array)$info);
			if ( ! $this->_mod()->can_do($info, $action)) continue;

			// Chuyen den ham duoc yeu cau
			if (in_array($action, array('on', 'off')))
			{
				$this->_action_option($info, $action);
			}
			else
			{
				$this->{'_'.$action}($info);
			}
		}
	}

	/**
	 * Xu ly hanh dong voi cac thuoc tinh
	 */
	function _action_option($info, $action)
	{
		// Xu ly voi cac option
		$data = array();
		switch ($action)
		{
			case 'on':
			{
				$data['status'] = '1';
				break;
			}
			case 'off':
			{
				$data['status'] = '0';
				break;
			}
		}

		// Cap nhat data
		if (count($data))
		{
			$this->_model()->update($info->id, $data);
			$output = json_encode(array('complete' => TRUE));
			set_output('json', $output);
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
				$this->widget_model->update_field($id, 'sort_order', $i+1);
			}

			$output = json_encode(array('complete' => TRUE));
			set_output('json', $output);
		}


		// Lay thong tin
		$regions = $this->_get_regions();

		// Tai file thanh phan
		$this->load->helper('form');
		$this->load->model('module_model');

		// Tao filter
		$filter_input 	= array();
		$filter_fields 	= array('id', 'name', 'module', 'widget', 'module_widget', 'region', 'layout', 'status');
		$filter = $this->widget_model->filter_create($filter_fields, $filter_input);
		$this->data['filter'] = $filter_input;

		// Lay danh sach
		$input 	= array();
		$input['order'] = array(array('widget.region', 'asc'), array('widget.sort_order', 'asc'));
		$list = $this->widget_model->filter_get_list($filter, $input);

		$actions = array('edit', 'del', 'on', 'off', 'translate');
		$list = admin_url_create_option($list, strtolower(__CLASS__), 'id', $actions);
		foreach ($list as $row)
		{
			$row->_region = array_get($regions, "{$row->region}.name", $row->region);
			$row->_url_translate = admin_url("translate/table/widget/{$row->id}");

			foreach ($actions as $action)
			{
				$row->{'_can_'.$action} = ($this->_mod()->can_do($row, $action) && admin_permission_url($row->{'_url_'.$action})) ? TRUE : FALSE;
			}
		}
		$this->data['list'] = $list;

		// Luu cac bien gui den view
		$this->data['action'] 	= current_url();
		$this->data['modules'] 	= $this->_get_list_module();
		$this->data['regions'] 	= $regions;
		$this->data['layouts'] 	= $this->_get_layouts();
		$this->data['status'] 	= config('status', 'main');
		$this->data['url_update_order'] = current_url().'?act=update_order';
		
		// Breadcrumbs
		$breadcrumbs = array();
		$breadcrumbs[] = array(admin_url('widget'), lang('mod_widget'));
		$breadcrumbs[] = array(current_url(), lang('list'));
		$this->data['breadcrumbs'] = $breadcrumbs;
		
		// Hien thi view
		$this->_display();
	}
	
}