<?php

use Core\Support\Arr;

class Bank extends MY_Controller
{
	/**
	 * Ham khoi dong
	 */
	public function __construct()
	{
		parent::__construct();
		
		$this->lang->load('admin/bank');
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
	protected function _set_rules($params)
	{
		$rules = array();

		$rules['name'] = array('name', 'required|trim|xss_clean');

		foreach (array('branch', 'acc_id', 'acc_name') as $p)
		{
			$rules[$p] = array($p, 'trim|xss_clean');
		}
		
		//$rules['image'] = array('image', 'callback__check_image');
		
		$this->form_validation->set_rules_params($params, $rules);
	}

	/**
	 * Kiem tra image
	 */
	public function _check_image()
	{
		if ( ! $this->_get_image())
		{
			$this->form_validation->set_message(__FUNCTION__, lang('required'));
			return FALSE;
		}
		
		return TRUE;
	}
	
	/**
	 * Lay image
	 */
	protected function _get_image($id = NULL)
	{
		if (is_null($id))
		{
			$id = $this->_get_id_cur();
		}
		
		$image = $this->model->file->get_info_of_mod('bank', $id, 'image', 'id, file_name');
		
		return $image;
	}
	
	/**
	 * Cap nhat image
	 */
	protected function _update_image($id)
	{
		// Lay thong tin cua file
		$file = $this->_get_image($id);
		if ( ! $file)
		{
			$file = new stdClass();
			$file->id = 0;
			$file->file_name = '';
		}
		
		// Cap nhat du lieu vao data
		$data = array();	
		$data['image_id']	= $file->id;
		$data['image_name']	= $file->file_name;
		$this->model->bank->update($id, $data);
	}
	
	/**
	 * Lay id xu ly hien tai
	 * 
	 * @return int
	 */
	protected function _get_id_cur()
	{
		return ($this->uri->rsegment(2) == 'add')
			? fake_id_get('bank') 
			: $this->uri->rsegment(3);
	}
	
	/**
	 * Lay input
	 */
	protected function _get_input($param = '')
	{
	    $data = array();
	    foreach (array(
			'name','branch', 'fee_constant', 'fee_percent', 'fee_min', 'fee_max', 'amount_min', 'amount_max',
			'acc_id', 'acc_name', 'use_in_deposit','use_in_withdraw','use_in_order','status',
		) as $p)
	    {
	        $data[$p] = $this->input->post($p);
	    }
	    
	    foreach (array('fee_constant', 'fee_percent', 'fee_min', 'fee_max', 'amount_min', 'amount_max') as $p)
	    {
	    	$data[$p] = currency_handle_input($data[$p]);
	    }

	    $image = $this->_get_image();
	    if ($image)
	    {
	    	$data['image_id']	= $image->id;
	    	$data['image_name']	= $image->file_name;
	    }

		$data = Arr::mapNullToEmptyString($data);

	    return ($param) ? $data[$param] : $data;
	}
	
	/**
	 * Tao data gui den view
	 *
	 * @param int $id
	 */
	protected function _create_view_data($id)
	{
		// Khai bao cac bien cua widget upload
		$widget_upload = array();
		$widget_upload['mod'] 			= 'single';
		$widget_upload['file_type'] 	= 'image';
		$widget_upload['status'] 		= config('file_public', 'main');
		$widget_upload['table'] 		= 'bank';
		$widget_upload['table_id'] 		= $id;
		$widget_upload['table_field'] 	= 'image';
		$widget_upload['resize'] 		= TRUE;
		$widget_upload['thumb'] 		= FALSE;
		$widget_upload['url_update']	= ($id > 0) ? current_url().'?act=update_image' : null;
		$this->data['widget_upload'] 	= $widget_upload;
		
		// Other
		$this->data['action'] = current_url();
	}
	
	
/*
 * ------------------------------------------------------
 *  Action handle
 * ------------------------------------------------------
 */
	/**
	 * Them moi
	 */
	public function add()
	{
		$fake_id = fake_id_get('bank');
		
		$form = array();
		
		$form['validation']['params'] = array(
			'name','branch', 'fee_constant', 'fee_percent', 'fee_min', 'fee_max', 'amount_min', 'amount_max',
			'acc_id', 'acc_name', 'image',
		);
		
		$form['submit'] = function($params) use ($fake_id)
		{
			// Lay input
			$data = $this->_get_input();
			
			// Cap nhat vao data
			$id = 0;
			$this->model->bank->create($data, $id);
			
			// Cap nhat lai table_id table file
			$this->model->file->update_table_id_of_mod('bank', $fake_id, $id);
			fake_id_del('bank');
			
			set_message(lang('notice_add_success'));
			
			return admin_url('bank');
		};
		
		$form['form'] = function() use ($fake_id)
		{
			$this->_create_view_data($fake_id);
			
			$this->_display('form');
		};
		
		$this->_form($form);
	}
	
	/**
	 * Chinh sua
	 */
	protected function _edit($info)
	{
		$info = $this->_mod()->add_info($info);
		$this->data['info'] = $info;
		
		// Cap nhat thong tin
		if ($this->input->get('act') == 'update_image')
		{
			$this->_update_image($info->id);
			return;
		}
		
		// Form
		$form = array();

		$form['validation']['params'] = array(
			'name','branch', 'fee_constant', 'fee_percent', 'fee_min', 'fee_max', 'amount_min', 'amount_max',
			'acc_id', 'acc_name', 'image',
		);
		
		$form['submit'] = function($params) use ($info)
		{
			$data = $this->_get_input();
			$this->model->bank->update($info->id, $data);
			
			set_message(lang('notice_update_success'));
			
			return admin_url('bank');
		};

		$form['form'] = function() use ($info)
		{
			$this->_create_view_data($info->id);
			
			$this->_display('form');
		};
		
		$this->_form($form);
	}
	
	/**
	 * Xoa
	 */
	protected function _del($info)
	{
		$this->model->bank->del($info->id);

		$this->load->helper('file');
		file_del_table('bank', $info->id);
		
		set_message(lang('notice_del_success'));
	}
	
	/**
	 * Danh sach
	 */
	public function index()
	{
		$list = array();
		$list['page'] = false;
		$list['sort'] = true;
		$this->_list($list);
	}
	
}