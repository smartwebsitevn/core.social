<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ads_location extends MY_Controller {
	
	function __construct()
	{
		parent::__construct();
		
		// loading the lang files
		$this->load->model('ads_location_model');
		$this->lang->load('admin/ads_banner');
	}
	
	/**
	 * Remap method
	 */
	public function _remap($method, $params = array())
	{
		return $this->_remap_action($method, $params, array('edit', 'del'));
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
		$rules['name'] 				= array('name', 'required|trim|xss_clean');
		$rules['code'] 				= array('code', 'required|trim|xss_clean|alpha_dash|callback__check_code');
		$rules['banner_width'] 		= array('banner_width', 'trim|is_natural');
		$rules['banner_height'] 	= array('banner_height', 'trim|is_natural');
		$rules['banner_quantity'] 	= array('banner_quantity', 'trim|is_natural');
		
		$this->form_validation->set_rules_params($params, $rules);
	}
	
	/**
	 * Kiem tra code nay da duoc su dung chua
	 */
	function _check_code($value)
	{
		$id = $this->uri->rsegment(3);
		$id = (!is_numeric($id)) ? 0 : $id;
		
		$where = array();
		$where['id !='] = $id;
		$where['code'] = $value;
		$id = $this->ads_location_model->get_id($where);
		
		if ($id)
		{
			$this->form_validation->set_message(__FUNCTION__, lang('notice_already_used'));
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
			$params = array('name', 'code', 'banner_width', 'banner_height'/*, 'banner_quantity'*/);
			$this->_set_rules($params);
			
			// Xu ly du lieu
			$result = array();
			if ($this->form_validation->run())
			{
				// Cap nhat vao data
				$data = array();
				foreach ($params as $p)
				{
					$data[$p] = $this->input->post($p);
				}
				$this->ads_location_model->create($data);
				
				// Khai bao du lieu tra ve
				$result['complete'] = TRUE;
				$result['location'] = admin_url('ads_location');
				set_message(lang('notice_add_success'));
			}
			else
			{
				foreach ($params as $param)
				{
					$result[$param] = form_error($param);
				}
			}
			
			$output = json_encode($result);
			set_output('json', $output);
		}
		
		
		// Luu bien gui den view
		$this->data['action'] = current_url();
		
		// Breadcrumbs
		$breadcrumbs = array();
		$breadcrumbs[] = array(admin_url('ads_location'), lang('mod_ads_location'));
		$breadcrumbs[] = array('', lang('add'));
		$this->data['breadcrumbs'] = $breadcrumbs;
		
		// Hien thi view
		$this->_display('form');
	}
	
	/**
	 * Chinh sua
	 */
	function _edit($info)
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
			$params = array('name', 'code', 'banner_width', 'banner_height'/*, 'banner_quantity'*/);
			$this->_set_rules($params);
			
			// Xu ly du lieu
			$result = array();
			if ($this->form_validation->run())
			{
				// Cap nhat vao data
				$data = array();
				foreach ($params as $p)
				{
					$data[$p] = $this->input->post($p);
				}
				$this->ads_location_model->update($info->id, $data);
				
				// Khai bao du lieu tra ve
				$result['complete'] = TRUE;
				$result['location'] = admin_url('ads_location');
				set_message(lang('notice_update_success'));
			}
			else
			{
				foreach ($params as $param)
				{
					$result[$param] = form_error($param);
				}
			}
			
			$output = json_encode($result);
			set_output('json', $output);
		}
		
		
		// Luu cac bien gui den view
		$this->data['action'] = current_url();
		$this->data['info'] = $info;
		
		// Breadcrumbs
		$breadcrumbs = array();
		$breadcrumbs[] = array(admin_url('ads_location'), lang('mod_ads_location'));
		$breadcrumbs[] = array('', lang('edit'));
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
		$this->ads_location_model->del($info->id);
		
		// Gui thong bao
		set_message(lang('notice_del_success'));
		return TRUE;
	}
	
	/**
	 * Thuc hien tuy chinh
	 */
	protected function _action($action)
	{
		// Lay input
		$mod = $this->uri->rsegment(1);
		
		$ids = $this->uri->rsegment(3);
		$ids = ( ! $ids) ? $this->input->post('id') : $ids;
		
		// Thuc hien action
		foreach ((array) $ids as $id)
		{
			// Xu ly id
			$id = ( ! is_numeric($id)) ? 0 : $id;
			
			// Kiem tra id
			$info = $this->model->{$mod}->get_info($id);
			if ( ! $info) continue;
			
			// Kiem tra co the thuc hien hanh dong nay khong
			//if ( ! $this->mod->{$mod}->can_do($info, $action)) continue;
			
			// Chuyen den ham duoc yeu cau
			$this->{'_'.$action}($info);
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
		// Lay danh sach
		$list = $this->ads_location_model->get_list();
		$actions = array('edit', 'del');
		$list = admin_url_create_option($list, strtolower(__CLASS__), 'id', $actions);
		foreach ($list as $row)
		{
			foreach ($actions as $action)
			{
				$row->{'_can_'.$action} = ($this->_mod()->can_do($row, $action) && admin_permission_url($row->{'_url_'.$action})) ? TRUE : FALSE;
			}
		}
		$this->data['list'] = $list;
		
		// Breadcrumbs
		$breadcrumbs = array();
		$breadcrumbs[] = array(admin_url('ads_location'), lang('mod_ads_location'));
		$breadcrumbs[] = array('', lang('list'));
		$this->data['breadcrumbs'] = $breadcrumbs;
		
		// Hien thi view
		$this->_display();
	}
	
}
	
?>