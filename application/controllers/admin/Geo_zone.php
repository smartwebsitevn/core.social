<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Geo_zone extends MY_Controller {
	public $params = array(
		'name' => 'required|trim|xss_clean',
		'description' => 'required|trim|xss_clean',
		'created_date' => 'trim|xss_clean',
	);
	public $filter = array('name');
	public $actions = array('edit', 'del', 'translate');

	/**
	 * Ham khoi dong
	 */
	function __construct()
	{
		parent::__construct();
		$this->lang->load('common');
		$this->lang->load('admin/country');
		$this->lang->load('admin/geo_zone');
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
					if( $key == 'created_date' ) 
					{
						$data['created_date'] 	= now();
						continue;
					}
					if( $key == 'geo_zone_to_city' ) 
					{
						continue;
					}
					if( $key == 'rate' )
					{
						$rate = (String)$this->input->post( $key );
						$rate = str_replace( ',', '', $rate);
						$data[ $key ] = (float)$rate;
						continue;
					}

					$data[ $key ] = $this->input->post( $key );
				}

				// Lay country_id vua them
				$geo_zone_id = 0;
				$this->_model()->create($data, $geo_zone_id);

				$this->_mod()->to_geo_zone_to_city( $geo_zone_id, $this->input->post( 'geo_zone_to_city' ) );

				
				// Khai bao du lieu tra ve
				$result['complete'] = TRUE;
				$result['location'] = admin_url('geo_zone');
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
		$this->data['geo_zone_to_city'] = model('geo_zone_to_city')->filter_get_list( array( 'geo_zone_id' => $info->id ) );
		
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
					if( $key == 'created_date' ) 
					{
						$data['created_date'] 	= now();
						continue;
					}
					if( $key == 'geo_zone_to_city' ) 
					{
						continue;
					}
					if( $key == 'rate' )
					{
						$rate = (String)$this->input->post( $key );
						$rate = str_replace( ',', '', $rate);
						$data[ $key ] = (float)$rate;
						continue;
					}

					$data[ $key ] = $this->input->post( $key );
				}

				$this->_model()->update($info->id, $data);

				$this->_mod()->to_geo_zone_to_city( $info->id, $this->input->post( 'geo_zone_to_city' ) );
			

				
				

				// Khai bao du lieu tra ve
				$result['complete'] = TRUE;
				$result['location'] = admin_url('geo_zone');
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
		$this->data['city'] = model('city')->get_list();
		
		
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
			$this->{'_'.$action}($info);
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
			$row = $this->_mod()->url($row);
			$row->_url_translate = admin_url("translate/table/geo_zone/{$row->id}");
			
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
	 * Load city
	 */
	function loadCity()
	{
		$id = $_POST['value'];

		$model = model('city')->filter_get_list( array( 'country_id' => $id ) );

		set_output ('text', json_encode($model) );
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