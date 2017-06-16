<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Slider extends MY_Controller
{
	/**
	 * Ham khoi dong
	 */
	public function __construct()
	{
		parent::__construct();
		
		// Tai cac file thanh phan
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
	 * Gan dieu kien cho cac bien
	 */
	protected function _set_rules($params)
	{
		$rules = array();
		$rules['key']     = array('key', 'required|trim|alpha_dash|callback__check_key');
		$rules['name']    = array('name', 'required|trim|xss_clean');
		
		$this->form_validation->set_rules_params($params, $rules);
	}
	
	/**
	 * Kiem tra key
	 */
	function _check_key($value)
	{
		$id = $this->uri->rsegment(3);
		$id = ( ! is_numeric($id)) ? 0 : $id;
		
		$where = array();
		$where['id !=']   = $id;
		$where['key']     = $value;
		$id = $this->model->slider->get_id($where);
		
		if ($id)
		{
			$this->form_validation->set_message(__FUNCTION__, lang('notice_already_used'));
			return FALSE;
		}
		
		return TRUE;
	}
	
	/**
	 * Lay input
	 */
	protected function _get_input($param = '')
	{
		$data = array();
		foreach (array('key', 'name') as $p)
		{
			$data[$p] = $this->input->post($p);
		}
		
		return ($param) ? $data[$param] : $data;
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
		
		$form['validation']['params'] = array('key', 'name');
		
		$form['submit'] = function($params)
		{
			$data = $this->_get_input();
			$this->model->slider->create($data);
			
			set_message(lang('notice_add_success'));
			
			return admin_url('slider');
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
		$this->data['info'] = $info;
		
		$form = array();
		
		$form['validation']['params'] = array('key', 'name');
		
		$form['submit'] = function($params) use ($info)
		{
			$data = $this->_get_input();
			$this->model->slider->update($info->id, $data);
			
			set_message(lang('notice_update_success'));
			
			return admin_url('slider');
		};
		
		$form['form'] = function()
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
		$this->model->slider->del($info->id);
		
		set_message(lang('notice_del_success'));
	}
	
	/**
	 * Danh sach
	 */
	public function index()
	{
		$this->_list();
	}
	
}