<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Currency extends MY_Controller {
	
	/**
	 * Ham khoi dong
	 */
	function __construct()
	{
		parent::__construct();

		// Tai cac file thanh phan
		$this->load->model('currency_model');
		$this->lang->load('admin/currency');

		redirect();
	}
	
	/**
	 * Remap method
	 */
	function _remap($method)
	{
		if (in_array($method, array('edit', 'del', 'set_default')))
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
		$id = (int) data_get($this->data, 'info.id');

		$rules = array();
		$rules['name'] 				= array('name', 'required|trim|xss_clean');
		$rules['code'] 				= array('code', 'required|alpha_dash|is_unique[currency,code,'.$id.']');
		$rules['value'] 			= array('rate', 'required|trim|callback__check_value');
		$rules['decimal'] 			= array('decimal', 'required|trim|is_natural');
		$rules['symbol_left'] 		= array('symbol_left', 'trim|xss_clean');
		$rules['symbol_right'] 		= array('symbol_right', 'trim|xss_clean');
		$rules['purse_prefix'] 		= array('purse_prefix', 'required|alpha_numeric|exact_length[3]');

		$this->form_validation->set_rules_params($params, $rules);
	}
	
	/**
	 * Kiem tra gia tri cua tien te
	 */
	function _check_value($value)
	{
		$value = floatval($value);
		if ($value <= 0.0)
		{
			$this->form_validation->set_message(__FUNCTION__, lang('notice_value_error'));
			return FALSE;
		}
		
		return TRUE;
	}
	
	/**
	 * Tu dong kiem tra gia tri cua bien
	 */
	function _autocheck($param)
	{
		$this->_set_rules($param);
		
		$result = array();
		$result['accept'] = $this->form_validation->run();
		$result['error'] = form_error($param);

		$output = json_encode($result);
		set_output('json', $output);
	}
	
	
/*
 * ------------------------------------------------------
 *  Action handle
 * ------------------------------------------------------
 */
	/**
	 * Them moi
	 */
	function add()
	{

		// Tai cac file thanh phan
		$this->load->library('form_validation');
		$this->load->helper('form');
		
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
			$params = array('name', 'code', 'symbol_left', 'symbol_right', 'decimal', 'value', 'purse_prefix');
			$this->_set_rules($params);
			
			// Xu ly du lieu
			$result = array();
			if ($this->form_validation->run())
			{
				// Cap nhat vao data
				$data = array();	
				$data['name']			= $this->input->post('name');
				$data['code']			= strtoupper($this->input->post('code'));
				$data['symbol_left']  	= $this->input->post('symbol_left');
				$data['symbol_right']  	= $this->input->post('symbol_right');
				$data['decimal']		= $this->input->post('decimal');
				$data['value']			= currency_handle_input($this->input->post('value'));
				$data['status'] 		= config( ($this->input->post('status')) ? 'status_on' : 'status_off' , 'main');
				$data['show'] 			= config( ($this->input->post('show')) ? 'status_on' : 'status_off' , 'main');
				$data['purse_prefix']	= strtoupper($this->input->post('purse_prefix'));
				$this->currency_model->create($data);
				
				// Khai bao ket qua tra ve
				$result['complete'] = TRUE;
				$result['location'] = admin_url('currency');
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
		
		
		// Luu bien gui den view
		$this->data['action'] = current_url();
		
		// Tao cay thu muc
		$breadcrumbs = array();
		$breadcrumbs[] = array('', lang('group_system'));
		$breadcrumbs[] = array(admin_url('currency'), lang('mod_currency'));
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
		// Lay tien te mac dinh
		$default 	= currency_get_default();
		$default_id = (isset($default->id)) ? $default->id : 0;

		// Xu ly info
		$info->value = floatval($info->value);
		$info->_is_default = ($info->id == $default_id) ? TRUE : FALSE;

		$this->data['info'] = $info;


		// Tai cac file thanh phan
		$this->load->library('form_validation');
		$this->load->helper('form');
		
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
			$params = array('name', 'code', 'symbol_left', 'symbol_right', 'decimal', 'value', 'purse_prefix');
			$this->_set_rules($params);
			
			// Xu ly du lieu
			$result = array();
			if ($this->form_validation->run())
			{
				// Cap nhat vao data
				$data = array();	
				$data['name']			= $this->input->post('name');
				$data['code']			= strtoupper($this->input->post('code'));
				$data['symbol_left']  	= $this->input->post('symbol_left');
				$data['symbol_right']  	= $this->input->post('symbol_right');
				$data['decimal']		= $this->input->post('decimal');
				$data['value']			= currency_handle_input($this->input->post('value'));
				$data['status'] 		= config( ($this->input->post('status')) ? 'status_on' : 'status_off' , 'main');
				$data['show'] 			= config( ($this->input->post('show')) ? 'status_on' : 'status_off' , 'main');
				$data['purse_prefix']	= strtoupper($this->input->post('purse_prefix'));
				$this->currency_model->update($info->id, $data);
				
				// Khai bao ket qua tra ve
				$result['complete'] = TRUE;
				$result['location'] = admin_url('currency');
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
		

		// Luu bien gui den view
		$this->data['action'] 	= current_url();
		$this->data['info'] 	= $info;
		
		// Tao cay thu muc
		$breadcrumbs = array();
		$breadcrumbs[] = array('', lang('group_system'));
		$breadcrumbs[] = array(admin_url('currency'), lang('mod_currency'));
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
		$this->currency_model->del($info->id);
		
		// Gui thong bao
		set_message(lang('notice_del_success'));
	}
	
	/**
	 * Thiet lap tien te mac dinh
	 */
	function _set_default($info)
	{
		// Cap nhat du lieu
		$this->currency_model->set_default($info->id);
		
		// Gui thong bao
		set_message(lang('notice_update_success'));
	}
	
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
			$info = $this->currency_model->get_info($id);
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
			case 'edit':
			{
				return TRUE;
			}
			
			case 'del':
			case 'set_default':
			{
				$default 	= currency_get_default();
				$default_id = (isset($default->id)) ? $default->id : 0;
				
				return ($info->id != $default_id) ? TRUE : FALSE;
			}
		}
		
		return FALSE;
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
		// Lay tien te mac dinh
		$default 	= currency_get_default();
		$default_id = (isset($default->id)) ? $default->id : 0;
		
		// Lay danh sach
		$list = $this->currency_model->get_list();
		
		$actions = array('edit', 'del', 'set_default');
		$list = admin_url_create_option($list, strtolower(__CLASS__), 'id', $actions);
		foreach ($list as $row)
		{
			$row->_is_default = ($row->id == $default_id) ? TRUE : FALSE;
			
			foreach ($actions as $action)
			{
				$row->{'_can_'.$action} = ($this->_can_do($row, $action) && admin_permission_url($row->{'_url_'.$action})) ? TRUE : FALSE;
			}
		}
		$this->data['list'] = $list;
		
		// Hien thi view
		$this->_display();
	}
	
}