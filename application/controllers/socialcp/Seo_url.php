<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Seo_url extends MY_Controller
{
	/**
	 * Ham khoi dong
	 */
	public function __construct()
	{
		parent::__construct();
		
		$this->lang->load('admin/'.$this->_get_mod());
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
		return array('url_original', 'url_seo', 'url_base');
	}
	
	/**
	 * Gan dieu kien cho cac bien
	 */
	protected function _set_rules($params)
	{
		$rules = array();
		$rules['url_original'] 	= array('url_original', 'required|trim|xss_clean');
		$rules['url_seo'] 		= array('url_seo', 'required|trim|xss_clean|callback__check_url_seo');
		$rules['url_base'] 		= array('url_base', 'trim|xss_clean');
		
		$this->form_validation->set_rules_params($params, $rules);
	}
	
	/**
	 * Kiem tra url_seo
	 */
	public function _check_url_seo($value)
	{
		// Xu ly input
		$value = $this->_get_input('url_seo');
		
		// Kiem tra xem co trung voi routes cua he thong hay khong
		if (isset($this->router->routes[$value]))
		{
			$this->form_validation->set_message(__FUNCTION__, lang('notice_already_exists'));
			return FALSE;
		}
		
		// Kiem tra xem da ton tai trong seo_url hay chua
		$id = $this->_model()->get_id(array(
			'id !=' => $this->uri->rsegment(3),
			'url_seo' => $value,
		));
		
		if ($id)
		{
			$this->form_validation->set_message(__FUNCTION__, lang('notice_already_exists'));
			return FALSE;
		}
		
		return TRUE;
	}
	
	/**
	 * Lay input
	 */
	protected function _get_input($param = null)
	{
		// Lay input
		$data = array();
		foreach ($this->_get_params() as $p)
		{
			$v = $this->input->post($p);
			$v = filter_var($v, FILTER_VALIDATE_URL) ? url_get_uri($v) : $v;
			$v = trim($v, '/');
			
			$data[$p] = $v;
		}
		
		// Xu ly url_base
		if ( ! $data['url_base'])
		{
			$data['url_base'] = $this->_mod()->get_route_base($data['url_original']);
		}
	    
	    return array_get($data, $param);
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
		$form = array();
		
		$form['validation']['params'] = $this->_get_params();
		
		$form['submit'] = function($params)
		{
			$data = $this->_get_input();
			$this->_model()->create($data);
			
			set_message(lang('notice_add_success'));
			
			return $this->_url();
		};
		
		$form['form'] = function()
		{
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
		
		// Form
		$form = array();

		$form['validation']['params'] = $this->_get_params();
		
		$form['submit'] = function($params) use ($info)
		{
			$data = $this->_get_input();
			$this->_model()->update($info->id, $data);
			
			set_message(lang('notice_update_success'));
			
			return $this->_url();
		};

		$form['form'] = function() use ($info)
		{
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

		set_message(lang('notice_del_success'));
	}
	
	/**
	 * Danh sach
	 */
	public function index()
	{
		$list = array();
		$list['filter'] = true;
		$list['filter_fields'] = array('id', 'key', 'url_original', 'url_seo', 'url_base');
		$this->_list($list);
	}
	
}