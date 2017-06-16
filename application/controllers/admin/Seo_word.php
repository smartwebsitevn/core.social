<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Seo_word extends MY_Controller
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
		return array('url', 'title', 'description', 'keywords');
	}
	
	/**
	 * Gan dieu kien cho cac bien
	 */
	protected function _set_rules($params)
	{
		$rules = array();
		$rules['url'] 			= array('url', 'required|trim|xss_clean|callback__check_url');
		$rules['title'] 		= array('title', 'trim|xss_clean');
		$rules['description'] 	= array('description', 'trim|xss_clean');
		$rules['keywords'] 		= array('keywords', 'trim|xss_clean');
		
		$this->form_validation->set_rules_params($params, $rules);
	}
	
	/**
	 * Kiem tra url
	 */
	public function _check_url($value)
	{
		$value = trim($value, '"');
		
		if ( ! url_site_valid($value))
		{
			$this->form_validation->set_message(__FUNCTION__, lang('notice_value_invalid'));
			return FALSE;
		}
		
		return TRUE;
	}
	
	/**
	 * Lay input
	 */
	protected function _get_input($param = null)
	{
		$data = array();
		foreach ($this->_get_params() as $p)
		{
			$data[$p] = $this->input->post($p);
		}
		
		$data['url'] = handle_content($data['url'], 'input');
	    
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
			$this->data['param_old_value'] = $this->_model()->_param_old_value;
			
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
			$this->data['param_old_value'] = $this->_model()->_param_old_value;
			
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
		$list['filter_fields'] = array('id', 'key', 'url', 'title');
		$this->_list($list);
	}
	
}