<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class emailsmtp extends MY_Controller
{
	/**
	 * Ham khoi dong
	 */
	public function __construct()
	{
		parent::__construct();
		
		$this->lang->load('admin/'.__CLASS__);
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
		$rules['host'] = array('host', 'required|trim|xss_clean');
		$rules['email'] = array('email', 'required|trim|xss_clean');
		$rules['password'] = array('password', 'required|trim|xss_clean');
		$rules['type'] = array('type', 'required|trim|xss_clean');
		foreach (array('timeout','port',
						 'limit_per_day','limit_per_send','limit_delay','active') as $p)
		{
			$rules[$p] = array($p, 'is_natural|trim|xss_clean');
		}
		
		//$rules['image'] = array('image', 'callback__check_image');
		
		$this->form_validation->set_rules_params($params, $rules);
	}

	/**
	 * Lay input
	 */
	protected function _get_input($param = '')
	{
	    $data = array();
	    foreach (array('timeout','host','email','password','port','type','active',
						 'limit_per_day','limit_per_send','limit_delay') as $p)
	    {
	        $data[$p] = $this->input->post($p);
	    }
		$this->load->library('encrypt');
		$data['password'] = $this->encrypt->encode($data['password']);
	    
	    return ($param) ? $data[$param] : $data;
	}
	
	/**
	 * Tao data gui den view
	 *
	 * @param int $id
	 */
	protected function _create_view_data()
	{

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
		$form = array();

		$form['validation']['params'] = array('timeout','host','email','password','port',
				'limit_per_day','limit_per_send','limit_delay','active','type');
		
		$form['submit'] = function($params)
		{
			// Lay input
			$data = $this->_get_input();
			
			// Cap nhat vao data
			$id = 0;
			$this->model->emailsmtp->create($data, $id);

			
			set_message(lang('notice_add_success'));
			
			return admin_url('emailsmtp');
		};
		
		$form['form'] = function()
		{
			$this->data['emailsmtp'] = config('emailsmtp', 'mod/emailsmtp');
			$this->_create_view_data();
			
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
		$this->load->library('encrypt');
		$info->password = $this->encrypt->decode($info->password);
		$this->data['info'] = $info;

		// Form
		$form = array();

		$form['validation']['params'] = array('timeout','host','email','password','port',
				'limit_per_day','limit_per_send','limit_delay','active','type');
		
		$form['submit'] = function($params) use ($info)
		{
			$data = $this->_get_input();
			$this->model->emailsmtp->update($info->id, $data);
			
			set_message(lang('notice_update_success'));
			
			return admin_url('emailsmtp');
		};

		$form['form'] = function() use ($info)
		{
			$this->data['emailsmtp'] = config('emailsmtp', 'mod/emailsmtp');
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
		$this->model->emailsmtp->del($info->id);

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