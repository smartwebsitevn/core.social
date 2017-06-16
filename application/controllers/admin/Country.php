<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Country extends MY_Controller {
	public $params = array(
		'name' => 'required|trim|xss_clean',
		'dial_code' => 'trim|xss_clean',
		'code' => 'trim|xss_clean',
		'status' => 'trim|xss_clean',
	);
	public $filter = array('name', 'code', 'group_id', 'status');
	public $actions = array('edit', 'del', 'on', 'off');


	function __construct()
	{
		parent::__construct();
		$this->lang->load('common');
		$this->lang->load('admin/country');
	}

	/**
	 * Remap method
	 */
	function _remap($method)
	{
		if (in_array($method, array('edit', 'del', 'on', 'off')))
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

	function initial($_autocheck)
	{
		// Tai cac file thanh phan
		$this->load->library('form_validation');
		$this->load->helper('form');

		// Tu dong kiem tra gia tri cua 1 bien
		$param = $_autocheck;
		if ($param)
			$this->_autocheck($param);
	}




	/**
	 * Them moi
	 */
	function add()
	{
		// Tao fake id tam thoi de cap nhat cho file dinh kem
		$this->initial($this->input->post('_autocheck'));
		
		// Xu ly form
		if ($this->input->post('_submit'))
		{
			// Gan dieu kien cho cac bien
			$this->_set_rules( array_keys($this->params) );
			
			// Xu ly du lieu
			$result = array();
			if ($this->form_validation->run())
			{
				// Them du lieu vao data
				$data = array();	
				foreach ($this->params as $key => $value) {
					$data[ $key ] = $this->input->post( $key );
				}

				/* Thực hiện thêm mới  */
				$country_id = 0;
				$this->_model()->create($data, $country_id);
				
				$result['complete'] = TRUE;
				$result['location'] = admin_url('country');
				set_message(lang('notice_add_success'));
			}
			
			// Neu du lieu khong phu hop
			if (empty($result['complete']))
			{
				foreach ($this->params as $key => $value)
				{
					$result[$key] = form_error($key);
				}
			}
			
			// Form output
			$this->_form_submit_output($result);
		}

		// Luu bien gui den view
		$this->data['action'] = current_url(true);
		
		$this->data['regions'] = model('country_group')->get_list();

		// Hien thi view
		$this->_display('form');
	}
	

















	/**
	 * Chinh sua
	 *
	 * 
	 */
	function _edit($info)
	{
		$this->initial($this->input->post('_autocheck'));
		
		// Xu ly form
		if ($this->input->post('_submit'))
		{
			// Gan dieu kien cho cac bien
			$this->_set_rules( array_keys($this->params) );
			
			// Xu ly du lieu
			$result = array();
			if ($this->form_validation->run())
			{
				// Cap nhat du lieu vao data
				$data = array();	
				foreach ($this->params as $key => $value) {
					$data[ $key ] = $this->input->post( $key );
				}

				$this->_model()->update($info->id, $data);

				// Khai bao du lieu tra ve
				$result['complete'] = TRUE;
				$result['location'] = admin_url('country');
				set_message(lang('notice_update_success'));

			}
			
			// Neu du lieu khong phu hop
			if (empty($result['complete']))
			{
				foreach ($this->params as $key => $value)
				{
					$result[$key] = form_error($key);
				}
			}
			
			// Form output
			$this->_form_submit_output($result);
		}

		// Xu ly thong tin
		$info = $this->_mod()->add_info($info);
		$this->data['info'] = $info;
			

		$this->data['regions'] = model('country_group')->get_list();

		// Luu cac bien gui den view
		$this->data['action'] = current_url(true);
		
		// Hien thi view
		$this->_display('form');
	}
	









	/**
	 * Xoa du lieu
	 */
	function _del($info)
	{
		// Xóa phòng
		if( model('city')->check_exits('country_id =' . $info->id) )
			model('city')->del_rule('country_id =' . $info->id);

		// Thuc hien xoa
		$this->_model()->del($info->id);

		
		// Gui thong bao
		set_message(lang('notice_del_success'));
	}











	/**
	 * Thuc hien tuy chinh
	 */
	function _action($action)
	{
		// Lay input
		$ids = $this->uri->rsegment(3);
		$ids = (!$ids) ? $this->input->post('id') : $ids;
		$ids = (!is_array($ids)) ? array($ids) : $ids;

		// Thuc hien action
		foreach ($ids as $id) {
			// Xu ly id
			$id = (!is_numeric($id)) ? 0 : $id;

			// Kiem tra id
			$info = $this->_model()->get_info($id);
			if (!$info) continue;

			// Kiem tra co the thuc hien hanh dong nay khong
			if (!$this->_mod()->can_do($info, $action)) continue;


			// Chuyen den ham duoc yeu cau
			if (in_array($action, array('on', 'off'))) {
				// thuc hien yeu cau
				set_message(lang('notice_update_success'));
				$this->_mod()->action($info, $action);
				$output = array('complete' => TRUE);
				set_output('json', json_encode($output));
				//$this->_action_option($info, $action);
			} else {
				$this->{'_' . $action}($info);
			}
		}
	}
	












	






	
	/*
	 * ------------------------------------------------------
	 *  Danh sach
	 * ------------------------------------------------------
	 */
	function index()
	{
		// Tai cac file thanh phan
		$this->load->helper('site');
		$this->load->helper('form');
		
		// Tao filter
		$filter_input 	= array();
		$filter = $this->_model()->filter_create($this->filter, $filter_input);
		$this->data['filter'] = $filter_input;
		
		// Lay tong so
		$total = $this->_model()->filter_get_total($filter);
		$page_size = config('list_limit', 'main');
		$limit = $this->input->get('per_page');
		$limit = max(0, min($limit, get_limit_page_last($total, $page_size)));
		
		// Lay danh sach
		$input = array();
		$input['limit'] = array($limit, $page_size);
		$list = $this->_model()->filter_get_list($filter, $input);
		
		$list = admin_url_create_option($list, strtolower(__CLASS__), 'id', $this->actions);
		foreach ($list as $row)
		{
			$row = $this->_mod()->region($row);

			// $row->_country = model('country')->get_info( $row->country );
			$row->_url_translate = admin_url("translate/table/country/{$row->id}");
			
			foreach ($this->actions as $action)
			{
				$row->{'_can_'.$action} = ($this->_mod()->can_do($row, $action) && admin_permission_url($row->{'_url_'.$action})) ? TRUE : FALSE;
			}
			
			foreach (array('edit') as $action)
			{
				$row->{'_url_'.$action} = url_add_return($row->{'_url_'.$action});
			}
		}
		$this->data['list'] = $list;
		
		// Tao chia trang
		$this->data['pages_config'] = array(
			'base_url' => current_url().'?'.url_build_query($filter_input),
			'page_query_string' => TRUE,
			'total_rows' => $total,
			'per_page' => $page_size,
			'cur_page' => $limit
		);
		
		
		$this->data['regions'] = model('country_group')->get_list();
		
		// Tao action list
		$actions = array();
		foreach (array('del') as $v)
		{
			$url = admin_url(strtolower(__CLASS__).'/'.$v);
			if ( ! admin_permission_url($url)) continue;
			
			$actions[$v] = $url;
		}
		$this->data['actions'] = $actions;

		// Luu bien gui den view
		$this->data['action'] 	= current_url();
		$this->data['options'] 	= $this->_model()->_options;
		
		// Hien thi view
		$this->_display();
	}



















	/*
	 * ------------------------------------------------------
	 *  Rule handle
	 * ------------------------------------------------------
	 */
	
	/**
	 * Gan dieu kien cho cac bien
	 */
	function _set_rules($params = array())
	{
		$rules = array();
		foreach ($params as $key) {
			$rules[ $key ] = array( $key, $this->params[ $key ] );
		}
		
		$this->form_validation->set_rules_params($params, $rules);
	}


	
	/**
	 * Tu dong kiem tra gia tri cua bien
	 */
	function _autocheck($param)
	{
		$this->_set_rules($param);
		
		$result = array();
		$result['accept'] = $this->form_validation->run();
		$result['error'] = form_error($param);
		
		$output = json_encode($result);
		set_output('json', $output);
	}
}