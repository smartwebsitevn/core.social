<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Slider_item extends MY_Controller
{
	/**
	 * Ham khoi dong
	 */
	public function __construct()
	{
		parent::__construct();

		$this->lang->load('admin/slider');
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
	 * Lay danh sach bien
	 * 
	 * @return array
	 */
	protected function _get_params()
	{
		return array('slider_id', 'url' ,'name','target', 'desc', 'sort_order', 'status');
	}
	
	/**
	 * Gan dieu kien cho cac bien
	 */
	protected function _set_rules($params)
	{
		$rules = array();
		$rules['slider_id'] 	= array('slider', 'required|trim|xss_clean');
		// $rules['image'] 		= array('image', 'callback__check_image');
		$rules['name'] 			= array('name', 'required|trim|xss_clean');
		$rules['target'] 			= array('target', 'required|trim|xss_clean');
		$rules['desc'] 			= array('desc', 'trim|xss_clean');
		$rules['url'] 			= array('url', 'trim|xss_clean');
		
		$this->form_validation->set_rules_params($params, $rules);
	}

	/**
	 * Kiem tra image
	 */
	// public function _check_image()
	// {
	// 	if ( ! $this->_get_image())
	// 	{
	// 		$this->form_validation->set_message(__FUNCTION__, lang('required'));
	// 		return FALSE;
	// 	}
		
	// 	return TRUE;
	// }
	
	/**
	 * Lay image
	 */
	protected function _get_image($id = NULL)
	{
		if (is_null($id))
		{
			$id = $this->_get_id_cur();
		}
		
		$image = model('file')->get_info_of_mod('slider_item', $id, 'image', 'id, file_name');
		
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
		$this->_model()->update($id, $data);
	}
	
	/**
	 * Lay id xu ly hien tai
	 * 
	 * @return int
	 */
	protected function _get_id_cur()
	{
		return ($this->uri->rsegment(2) == 'add')
			? fake_id_get('slider_item') 
			: $this->uri->rsegment(3);
	}
	
	/**
	 * Lay input
	 */
	protected function _get_input($param = '')
	{
	    $data = array();
	    foreach ($this->_get_params() as $p)
	    {
	        $data[$p] = $this->input->post($p);
	    }

		$data['url'] = handle_content($data['url'], 'input');
		$data['target'] = handle_content($data['target'], 'input');
		$data['desc'] = handle_content($data['desc'], 'input');

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
		// Lay danh sach slider
		$this->data['list_slider'] = model('slider')->get_list();
		
		// Khai bao cac bien cua widget upload
		$widget_upload = array();
		$widget_upload['mod'] 			= 'single';
		$widget_upload['file_type'] 	= 'image';
		$widget_upload['status'] 		= config('file_public', 'main');
		$widget_upload['table'] 		= 'slider_item';
		$widget_upload['table_id'] 		= $id;
		$widget_upload['table_field'] 	= 'image';
		$widget_upload['resize'] 		= FALSE;
		$widget_upload['thumb'] 		= FALSE;
		$widget_upload['url_update']	= ($id > 0) ? current_url().'?act=update_image' : null;
		$this->data['widget_upload'] 	= $widget_upload;
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
		$fake_id = fake_id_get('slider_item');
		
		$form = array();
		
		$form['validation']['params'] = array_merge($this->_get_params(), array('image'));
		
		$form['submit'] = function($params) use ($fake_id)
		{
			// Lay input
			$data = $this->_get_input();
			
			// Cap nhat vao data
			$id = 0;
			$this->_model()->create($data, $id);
			
			// Cap nhat lai table_id table file
			model('file')->update_table_id_of_mod('slider_item', $fake_id, $id);
			fake_id_del('slider_item');
			
			set_message(lang('notice_add_success'));
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

		$form['validation']['params'] = array_merge($this->_get_params(), array('image'));
		
		$form['submit'] = function($params) use ($info)
		{
			$data = $this->_get_input();
			$this->_model()->update($info->id, $data);
			
			set_message(lang('notice_update_success'));
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
		$this->_model()->del($info->id);

		$this->load->helper('file');
		file_del_table('slider_item', $info->id);
		
		set_message(lang('notice_del_success'));
	}
	
	/**
	 * Danh sach
	 */
	public function index()
	{
		// Luu cac bien gui den view
		$this->data['list_slider'] = model('slider')->get_list();
		
		$list = array();
		$list['filter'] = TRUE;
		$list['filter_fields'] = array('slider','status');
		$list['filter_value']['slider'] = $this->input->get('slider') ?: current($this->data['list_slider'])->id;
		$list['input']['relation'] = 'slider';
		$list['actions'] = array('edit', 'del');
		$list['page'] = false;
		$list['sort'] = true;
		$list['display'] = false;
		$this->_list($list);
		$this->data['filter']['slider']  = $list['filter_value']['slider'] ;
		
		$this->_display();
	}
	
}