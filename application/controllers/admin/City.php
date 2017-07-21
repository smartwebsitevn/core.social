<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class City extends MY_Controller {
	public $params = array(
		'name' => 'required|trim|xss_clean',
		'code' => 'trim|xss_clean',
		'country_id' => 'required|trim|xss_clean',
		'feature' => 'trim|xss_clean',
		'status' => 'trim|xss_clean'
	);
	public $filter = array('name', 'code', 'country_id', 'status','feature');
	public $actions = array('edit', 'del', 'on', 'off','feature', 'feature_del');

	/**
	 * Ham khoi dong
	 */
	function __construct()
	{
		parent::__construct();
		$this->lang->load('common');
		$this->lang->load('admin/country');
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

				// Lay country_id vua them
				$city_id = 0;
				$this->_model()->create($data, $city_id);
				
				// Khai bao du lieu tra ve
				$result['complete'] = TRUE;
				$result['location'] = admin_url('city');
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
		// Lay danh sach country
		$this->data['country'] = model('country')->get_list();
		
		// Luu bien gui den view
		$this->data['action'] = current_url(true);
		
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
				$result['location'] = admin_url('city');
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

		
		
		// Lay danh sach country
		$this->data['country'] = model('country')->get_list();
		
		// Xu ly thong tin
		$info = $this->_mod()->add_info($info);
		$this->data['info'] = $info;
		
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
		$ids = ( ! $ids) ? $this->input->post('id') : $ids;
		$ids = ( ! is_array($ids)) ? array($ids) : $ids;
		
		// Thuc hien action
		foreach ($ids as $id)
		{
			// Xu ly id
			$id = ( ! is_numeric($id)) ? 0 : $id;
			
			// Kiem tra id
			$info = $this->_model()->get_info($id);
			if ( ! $info) continue;
			
			// Kiem tra co the thuc hien hanh dong nay khong
			if ( ! $this->_mod()->can_do($info, $action)) continue;
			
			// Chuyen den ham duoc yeu cau
			if (in_array($action, array('feature', 'feature_del')))
			{
				$this->_action_option($info, $action);
			}
			else
			{
				$this->{'_'.$action}($info);
			}
		}
	}

	/**
	 * Xu ly hanh dong voi cac thuoc tinh
	 */
	function _action_option($info, $action)
	{
		// Xu ly voi cac option
		$data = array();
		switch ($action)
		{
			case 'feature':
			{
				$data[$action] = now();
				break;
			}
			case 'feature_del':
			{
				$p = preg_replace('#_del$#i', '', $action);
				$data[$p] = 0;
				break;
			}
		}

		// Cap nhat data
		if (count($data))
		{
			$this->_model()->update($info->id, $data);

			$output = json_encode(array('complete' => TRUE));
			set_output('json', $output);
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
		// Cap nhat sort_order
		if ($this->input->get('act') == 'update_order')
		{
			$items = $this->input->post('items');
			$items = explode(',', $items);

			foreach ($items as $i => $id)
			{
				$data = array();
				$data['sort_order']	= $i;
				$this->_model()->update($id, $data);
			}
			//$this->_model()->cache_update();

			$output = json_encode(array('complete' => TRUE));
			set_output('json', $output);
		}


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
			$row = $this->_mod()->url($row);

			$row->_country = model('country')->get_info( $row->country_id );
			$row->_url_translate = admin_url("translate/table/city/{$row->id}");
			
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
		
		// Tao action list
		$actions = array();
		foreach (array('del') as $v)
		{
			$url = admin_url(strtolower(__CLASS__).'/'.$v);
			if ( ! admin_permission_url($url)) continue;
			
			$actions[$v] = $url;
		}
		$this->data['actions'] = $actions;

		// Lay danh sach cat
		$this->data['country'] = model('country')->get_list();
		$this->data['sort_url_update'] = current_url().'?act=update_order';

		// Luu bien gui den view
		$this->data['action'] 	= current_url();
		$this->data['options'] 	= $this->_model()->_options;
		
		// Hien thi view
		$this->_display();
	}


	/**
	 * Remap method
	 */
	function _remap($method)
	{
		if (in_array($method, $this->actions))
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