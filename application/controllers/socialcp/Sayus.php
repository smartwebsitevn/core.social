<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sayus extends MY_Controller
{
	/**
	 * Ham khoi dong
	 */
	public function __construct()
	{
		parent::__construct();
		
		$this->lang->load('admin/sayus');
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
		foreach (array('name',/*'regency', 'phone',  'address',*/ 'say') as $p)
		{
			$rules[$p] = array($p, 'required|trim|xss_clean');
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
		
		$image = $this->model->file->get_info_of_mod('sayus', $id, 'image', 'id, file_name');
		
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
		$this->model->sayus->update($id, $data);
	}
	
	/**
	 * Lay id xu ly hien tai
	 * 
	 * @return int
	 */
	protected function _get_id_cur()
	{
		return ($this->uri->rsegment(2) == 'add')
			? fake_id_get('sayus') 
			: $this->uri->rsegment(3);
	}
	
	/**
	 * Lay input
	 */
	protected function _get_input($param = '')
	{
	    $data = array();
	    foreach (array(
					 'name','regency',/* 'phone',  'address', */'say',
					'status','sort_order'
		) as $p)
	    {
	        $data[$p] = $this->input->post($p);
	    }

		$data['say'] = handle_content($data['say'], 'input');

	    $image = $this->_get_image();
	    if ($image)
	    {
	    	$data['image_id']	= $image->id;
	    	$data['image_name']	= $image->file_name;
	    }
	    
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
		$widget_upload['table'] 		= 'sayus';
		$widget_upload['table_id'] 		= $id;
		$widget_upload['table_field'] 	= 'image';
		$widget_upload['resize'] 		= FALSE;
		$widget_upload['thumb'] 		= TRUE;
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
		$fake_id = fake_id_get('sayus');
		
		$form = array();
		
		$form['validation']['params'] = array(
			'name','regency', 'phone',  'address', 'say', 'image',
		);
		
		$form['submit'] = function($params) use ($fake_id)
		{
			// Lay input
			$data = $this->_get_input();
			
			// Cap nhat vao data
			$id = 0;
			$this->model->sayus->create($data, $id);
			
			// Cap nhat lai table_id table file
			$this->model->file->update_table_id_of_mod('sayus', $fake_id, $id);
			fake_id_del('sayus');
			
			set_message(lang('notice_add_success'));
			
			return admin_url('sayus');
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
			'name','regency', 'phone',  'address', 'say', 'image',
		);
		
		$form['submit'] = function($params) use ($info)
		{
			$data = $this->_get_input();
			$this->model->sayus->update($info->id, $data);
			
			set_message(lang('notice_update_success'));
			
			return admin_url('sayus');
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
		$this->model->sayus->del($info->id);

		$this->load->helper('file');
		file_del_table('sayus', $info->id);
		
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