<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class News_cat extends MY_Controller
{
	/**
	 * Ham khoi dong
	 */
	public function __construct()
	{
		parent::__construct();
		
		// Tai cac file thanh phan
		$this->load->model('news_cat_model');
		$this->lang->load('admin/news');
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
		$rules['name'] 			= array('name', 'required|trim|xss_clean');
		
		$this->form_validation->set_rules_params($params, $rules);
	}
	
	/**
	 * Lay input
	 */
	protected function _get_input($param = '')
	{
		$data = elements(array('name', 'status','description', 'keywords','titleweb','url','parent_id'),
				$this->input->post(), '');

		$data['status'] = config( ($data['status']) ? 'status_on' : 'status_off' , 'main');
		if(!$data['url'])
			$data['url'] = $data['name'];
		$data['url'] = convert_vi_to_en($data['url']);
		$data['admin_id'] = admin_get_account_info()->id;
		$data['updated'] = now();
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
		$form['validation']['params'] = array('name');
		$form['submit'] = function($params)
		{
			$data = $this->_get_input();
			$data['sort_order'] = $this->news_cat_model->get_total() + 1;
			$this->news_cat_model->create($data);
			
			set_message(lang('notice_add_success'));
			
			return admin_url('news_cat');
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
		$form['validation']['params'] = array('name');
		$form['submit'] = function($params) use ($info)
		{
			$data = $this->_get_input();
			$this->news_cat_model->update($info->id, $data);
			
			set_message(lang('notice_update_success'));
			
			return admin_url('news_cat');
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
		$this->news_cat_model->del($info->id);
		
		set_message(lang('notice_del_success'));
	}
	
	/**
	 * Danh sach
	 */
	public function index()
	{
		$list = array();
		$list['filter'] = TRUE;
		$list['filter_fields'] = array('id', 'name','status');
		$list['page'] = FALSE;
		$list['sort'] = TRUE;
		$list['display'] = false;
		$this->_list($list);
		
		foreach ($this->data['list'] as $row)
		{
			$row = $this->_mod()->add_info($row);
			$row = $this->_mod()->url($row);
			$row = $this->_mod()->url_lang($row, strtolower(__CLASS__));
			$row->_updated_time = get_date($row->updated, 'time');
			$row->admin = admin_get_info($row->admin_id);
		}
		
		$this->_display();
	}
	/**
	 * Danh sach Menu Callback
	 */


	public function menu_callback()
	{
		$list = array();
		$list['filter'] = TRUE;
		$list['filter_fields'] = array('id', 'name','status');
		$list['page'] = FALSE;
		$list['display'] = false;
		$this->_list($list);

		foreach ($this->data['list'] as $row)
		{
			$row = $this->_mod()->add_info($row);
			$row = $this->_mod()->url($row);
			$row = $this->_mod()->url_lang($row);
		}

		$this->_display('menu_callback',null);
	}

	public function menu_holder_callback()
	{
		$list = array();
		$list['filter'] = TRUE;
		$list['filter_fields'] = array('id', 'name','status');
		$list['page'] = FALSE;
		$list['display'] = false;
		$this->_list($list);

		foreach ($this->data['list'] as $row)
		{
			$row = $this->_mod()->add_info($row);
			$row = $this->_mod()->url($row);
		}

		$this->_display('menu_holder_callback',null);
	}
}