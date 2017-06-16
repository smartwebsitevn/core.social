<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Faq_cat extends MY_Controller {
	
	/**
	 * Ham khoi dong
	 */
	function __construct()
	{
		parent::__construct();
		
		// Tai cac file thanh phan
		$this->load->model('faq_cat_model');
		$this->lang->load('admin/faq');
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
		$rules['name'] 			= array('name', 'required|trim|xss_clean');
		
		$this->form_validation->set_rules_params($params, $rules);
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
		$this->load->helper('form');
		$this->load->library('form_validation');

		// Tu dong kiem tra gia tri cua 1 bien
		$param = $this->input->post('_autocheck');
		if ($param)
		{
			$this->_form_autocheck($param);
		}
		
		// Xu ly form
		if ($this->input->post('_submit'))
		{
			// Gan dieu kien cho cac bien
			$params = array('name');
			$this->_set_rules($params);
			
			// Xu ly du lieu
			$result = array();
			if ($this->form_validation->run())
			{
				// Cap nhat vao data
				$data = array();
				$data['name']			= $this->input->post('name');
				$data['status']			= $this->input->post('status');
				$data['sort_order']		= time();
				$this->faq_cat_model->create($data);
				
				// Khai bao du lieu tra ve
				$result['complete'] = TRUE;
				$result['location'] = admin_url('faq_cat');
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
		
		
		// Luu cac bien gui den view
		$this->data['action'] = current_url();
		
		// Tao cay thu muc
		$breadcrumbs = array();
		$breadcrumbs[] = array(admin_url('faq_cat'), lang('mod_faq_cat'));
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
		// Tai cac file thanh phan
		$this->load->library('form_validation');
		$this->load->helper('form');

		// Tu dong kiem tra gia tri cua 1 bien
		$param = $this->input->post('_autocheck');
		if ($param)
		{
			$this->_form_autocheck($param);
		}
		
		// Xu ly form
		if ($this->input->post('_submit'))
		{
			// Gan dieu kien cho cac bien
			$params = array('name');
			$this->_set_rules($params);
			
			// Xu ly du lieu
			$result = array();
			if ($this->form_validation->run())
			{
				// Cap nhat du lieu vao data
				$data = array();
				$data['name']			= $this->input->post('name');
				$data['status']			= $this->input->post('status');
				$this->faq_cat_model->update($info->id, $data);
				
				// Khai bao du lieu tra ve
				$result['complete'] = TRUE;
				$result['location'] = admin_url('faq_cat');
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
		
		
		// Luu cac bien gui den view
		$this->data['action'] 	= current_url();
		$this->data['info'] 	= $info;
		
		// Tao cay thu muc
		$breadcrumbs = array();
		$breadcrumbs[] = array(admin_url('faq_cat'), lang('mod_faq_cat'));
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
		$this->faq_cat_model->del($info->id);
		
		// Gui thong bao
		set_message(lang('notice_del_success'));
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
				$this->faq_cat_model->update_field($id, 'sort_order', $i+1);
			}
			
			$output = json_encode(array('complete' => TRUE));
			set_output('json', $output);
		}
		
		
		// Lay danh sach
		$list = $this->faq_cat_model->get_list();
		
		$actions = array('edit', 'del', 'translate');
		$list = admin_url_create_option($list, strtolower(__CLASS__), 'id', $actions);
		foreach ($list as $row)
		{
			$row->_url_translate = admin_url("translate/table/faq_cat/{$row->id}");
			
			foreach ($actions as $action)
			{
				$row->{'_can_'.$action} = ($this->_mod()->can_do($row, $action) && admin_permission_url($row->{'_url_'.$action})) ? TRUE : FALSE;
			}
		}
		$this->data['list'] = $list;
		
		// Luu cac bien gui den view
		$this->data['sort_url_update'] = current_url().'?act=update_order';
		
		// Breadcrumbs
		$breadcrumbs = array();
		$breadcrumbs[] = array(admin_url('faq_cat'), lang('mod_faq_cat'));
		$breadcrumbs[] = array(current_url(), lang('list'));
		$this->data['breadcrumbs'] = $breadcrumbs;
		
		// Hien thi view
		$this->_display();
	}
	
}