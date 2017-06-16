<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class plan extends MY_Controller {
	
	/**
	 * Ham khoi dong
	 */
	function __construct()
	{
		parent::__construct();
		
		// Tai cac file thanh phan
		$this->load->model('plan_model');
		$this->lang->load('admin/plan');
	}

	/**
	 * Remap method
	 */
	function _remap($method)
	{

		if (in_array($method, array('edit', 'del')))
		{
			$this->_action($method);
		}
		elseif (method_exists($this, $method))
		{
			$this->{$method}();
		}
		else
		{
			show_404('', FALSE);
		}
	}
	/**
	 * Gan dieu kien cho cac bien
	 */
	function _set_rules($params = array())
	{
		if (!is_array($params))
		{
			$params = array($params);
		}
		
		$rules 				= array();
		$rules['name'] 		= array('name', 'required|trim|xss_clean');

		$rules['cost'] 			= array('cost', 'required|trim|callback__check_cost');
		$rules['discount'] 			= array('discount', 'trim|xss_clean');
		$rules['discount_type']     = array('discount_type', 'trim|callback__check_discount_type');
		$rules['day'] 			= array('day', 'required|trim|callback__check_day');
		
		foreach ($params as $param)
		{
			if (isset($rules[$param]))
			{
				$this->form_validation->set_rules($param, 'lang:'.$rules[$param][0], $rules[$param][1]);
			}
		}
	}

	/**
	 * Kiem tra nhom ho tro
	 */
	function _check_discount_type($value)
	{
		if (!$value)
		{
			return TRUE;
		}
	
		$discount_types = mod("product")->config('discount_types');
		if (!isset($discount_types[$value]))
		{
			$this->form_validation->set_message(__FUNCTION__, lang('notice_value_invalid'));
			return FALSE;
		}
	
		return TRUE;
	}
	
	/**
	 * Kiem tra gia
	 */
	function _check_cost($value)
	{
		$value = currency_handle_input($value);
		if ($value < 0)
		{
			$this->form_validation->set_message(__FUNCTION__, lang('notice_value_invalid'));
			return FALSE;
		}
	
		return TRUE;
	}
	
	/**
	 * Kiem tra day
	 */
	function _check_day($value)
	{
		$value = intval($value);
		if ($value <= 0)
		{
			$this->form_validation->set_message(__FUNCTION__, lang('notice_value_invalid'));
			return FALSE;
		}
	
		return TRUE;
	}
	
	/**
	 * Kiem tra gia tri cua ma so nap tien
	 */
	function _check_url($value)
	{
		$where = array();
		$where['id !='] = $this->uri->rsegment(4);
		$where['name'] = rtrim($value, '/');
		$id = $this->_model()->get_id($where);
		if ($id)
		{
			$this->form_validation->set_message(__FUNCTION__, $this->lang->line('notice_already_exists'));
			return FALSE;
		}
		
		return TRUE;
	}

	protected function _get_id_cur()
	{
		return ($this->uri->rsegment(2) == 'add')
			? fake_id_get($this->_get_mod())
			: $this->uri->rsegment(3);
	}
	/*
       * ------------------------------------------------------
       *  Prepare data handle
       * ------------------------------------------------------
       */
	protected function _fields()
	{
		return  array(
			'name','cost','discount','discount_type','day',
			'status'
		);
	}
	protected function _get_params()
	{
		$params = $this->_fields();
		//$package = mod('user')->config('packages');
		//$params = array_merge($params, $package);
		return $params;
	}

	/**
	 * Lay input
	 */

	protected function _get_inputs($param = '')
	{
		$data = array();
		$fields = $this->_fields();
		foreach ($fields as $f) {
			$v = $this->input->post($f);
			if (!$v) $v = '';

			if (in_array($f, array(
				'cost','discount',
			))) {
				$v= currency_handle_input($v);
			}

			$data[$f] = $v;
		}
		$data['status'] = config( ($data['status']) ? 'status_on' : 'status_off' , 'main');
		return ($param) ? $data[$param] : $data;
	}



	/**
	 * Tao data gui den view
	 *
	 * @param int $id
	 */
	protected function _create_form($id=null)
	{

		$this->data['discount_types'] = mod("product")->config('discount_types');
		$this->data['currency'] 	= currency_get_default();
		$this->data['action'] = current_url();
	}
	/**
	 * Thuc hien tuy chinh
	 */
	function action()
	{
		$action = $this->uri->rsegment(3);
		$id = $this->uri->rsegment(4);
		$id = (!is_numeric($id)) ? 0 : $id;

		// Kiem tra id
		$info = $this->_model()->get_info($id);
		if (!$info)
		{
			$this->session->set_flashdata('flash_message', array('warning', $this->lang->line('notice_page_not_found')));
			redirect_admin('plan');
		}

		// Chuyen den ham duoc yeu cau
		$this->{'_'.$action}($info);
	}

	/**
	 * Them moi
	 */
	function add()
	{
		$form = array();
		//$form['view'] = 'form';
		$form['validation']['params'] = $this->_get_params();
		$form['submit'] = function()
		{
			$data = $this->_get_inputs();
			$data['sort_order'] = $this->_model()->get_total() + 1;
			$this->_model()->create($data);
			set_message(lang('notice_add_success'));
			return $this->_url();
		};
		$form['form'] = function()
		{
			$this->_create_form();
			$this->_display('form');
		};
		$this->_form($form);

	}
	
	/**
	 * Chinh sua
	 */
	function _edit($info)
	{
		$this->data['info'] = $info;
		$form = array();
		//$form['view'] = 'form';
		$form['validation']['params'] = $this->_get_params();
		$form['submit'] = function()use($info)
		{
			$data = $this->_get_inputs();
			$this->_model()->update($info->id,$data);
			set_message(lang('notice_add_success'));
			return $this->_url();
		};
		$form['form'] = function()
		{
			$this->_create_form();
			$this->_display('form');
		};
		$this->_form($form);

	}
	

	/**
	 * Xoa du lieu
	 */
	function _del($info)
	{
		// Thuc hien xoa
		$this->_model()->del($info->id);
		// Gui thong bao
		set_message(lang('notice_del_success'));
		return TRUE;
	}


	
	/**
	 * Danh sach
	 */
	function index()
	{
		$list = array();
		$list['page'] = false;
		$list['sort'] = true;
		$this->_list($list);

	}

}