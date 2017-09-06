<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Card_type extends MY_Controller
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
		return array('key', 'name', 'provider', 'fee', 'provider_sub', 'image', 'status', 'profit');
	}
	
	/**
	 * Gan dieu kien cho cac bien
	 */
	protected function _set_rules($params)
	{
		$rules = array();
		
		foreach (array('key', 'name', 'provider', 'fee') as $p)
		{
			$rules[$p] = array($p, 'required|trim|xss_clean');
		}
		
		//$rules['fee_sub'] = array('fee_sub', 'trim|xss_clean');
		$rules['provider_sub'] = array('provider_sub', 'trim|xss_clean|callback__check_provider_sub');
		
		//$rules['image'] = array('image', 'callback__check_image');
		$this->form_validation->set_rules_params($params, $rules);
	}


	/**
	 * Kiem tra loai cua loai san pham
	 */
	public function _check_provider_sub($value)
	{
	    // Neu khong can khai bao
	    if ( ! $value)
	    {
	        return true;
	    }
	
	    $provider = $this->input->post('provider');
	    if ($provider == $value)
	    {
	        $this->form_validation->set_message(__FUNCTION__, lang('notice_provider_sub_not_exist'));
	        return false;
	    }
	
	    return true;
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
	protected function _get_image($id = null)
	{
		$id = is_null($id) ? $this->_get_id_cur() : $id;
		
		return model('file')->get_info_of_mod($this->_get_mod(), $id, 'image');
	}
	
	/**
	 * Cap nhat image
	 */
	protected function _update_image($id)
	{
		$file = $this->_get_image($id);
		if ( ! $file)
		{
			$file = (object) array('id' => 0, 'file_name' => '');
		}
		
		$this->_model()->update($id, array(
			'image_id' 		=> $file->id,
			'image_name' 	=> $file->file_name,
		));
	}
	
	/**
	 * Lay id xu ly hien tai
	 * 
	 * @return int
	 */
	protected function _get_id_cur()
	{
		return ($this->uri->rsegment(2) == 'add')
			? fake_id_get($this->_get_mod()) 
			: $this->uri->rsegment(3);
	}
	
	/**
	 * Lay input
	 */
	protected function _get_input($param = null)
	{
	    $data = array();
	    foreach (array('key', 'name', 'provider', 'fee','provider_sub', 'fee_user_group', 'status', 'profit') as $p)
	    {
	        $data[$p] = $this->input->post($p);
	    }

	    $data['fee'] = min(max(0, currency_handle_input($data['fee'])), 100);
	    //$data['fee_sub'] = min(max(0, currency_handle_input($data['fee_sub'])), 100);
	    $data['profit'] = min(max(0, currency_handle_input($data['profit'])), 100);
	    
	    $data['fee_user_group'] = $this->_make_fee_user_group_value( $data['fee_user_group']);
	    $data['fee_user_group'] = json_encode( $data['fee_user_group']);
	    
	    $image = $this->_get_image();
	    if ($image)
	    {
	    	$data['image_id']	= $image->id;
	    	$data['image_name']	= $image->file_name;
	    }
	    
	    return array_get($data, $param);
	}
	
	/**
	 * Tao gia tri cua fee_user_group
	 * 
	 * @param array $input
	 * @return array
	 */
	protected function _make_fee_user_group_value($input)
	{
		$input = is_array($input) ? $input : [];
		
		foreach ($input as &$value)
		{
			$value = min(max(0, currency_handle_input($value)), 100);
		}
		
		return array_filter($input);
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
		$widget_upload['table'] 		= $this->_get_mod();
		$widget_upload['table_id'] 		= $id;
		$widget_upload['table_field'] 	= 'image';
		$widget_upload['resize'] 		= TRUE;
		$widget_upload['thumb'] 		= FALSE;
		$widget_upload['url_update']	= ($id > 0) ? current_url().'?act=update_image' : null;
		$this->data['widget_upload'] 	= $widget_upload;
		
		$this->data['providers'] = $this->_get_providers();
		
		$this->data['keys'] = $this->_get_keys();
		
		$this->data['user_groups'] = model('user_group')->get_list();
	}
	
	/**
	 * Lay danh sach providers
	 * 
	 * @return array
	 */
	protected function _get_providers()
	{
		$list = model('payment_card')->get_list_installed();
		$list = model('payment_card')->get_list_info($list);
		
		return $list;
	}
	
	/**
	 * Lay danh sach key
	 * 
	 * @return array
	 */
	protected function _get_keys()
	{
		$types = config('types', 'payment_card');
		
		$list = array();
		foreach ($types as $type)
		{
			$list[] = (object) array('key' => $type, 'name' => $type);
		}
		
		return $list;
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
		$fake_id = fake_id_get($this->_get_mod());
		
		$form = array();
		
		$form['validation']['params'] = $this->_get_params();
		
		$form['submit'] = function($params) use ($fake_id)
		{
			$data = $this->_get_input();
			
			$id = 0;
			$this->_model()->create($data, $id);
			
			model('file')->update_table_id_of_mod($this->_get_mod(), $fake_id, $id);
			fake_id_del($this->_get_mod());
			
			set_message(lang('notice_add_success'));
			
			return $this->_url();
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
		file_del_table($this->_get_mod(), $info->id);
		
		set_message(lang('notice_del_success'));
	}
	
	/**
	 * Danh sach
	 */
	public function index()
	{
		$this->data['providers'] = $this->_get_providers();
		
		$list = array();
		$list['filter'] = TRUE;
		$list['filter_fields'] = array('id', 'name', 'provider', 'status');
		$list['sort'] = true;
		$this->_list($list);
	}
	
}