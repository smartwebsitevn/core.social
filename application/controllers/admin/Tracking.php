<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class tracking extends MY_Controller {
	public $cats_id = array();
	/**
	 * Ham khoi dong
	 */
	function __construct()
	{
		parent::__construct();
		
		// Tai cac file thanh phan
		$this->lang->load('admin/'.__CLASS__);
		$this->lang->load(__CLASS__);
		$this->data['class'] = __CLASS__;
	}

	public $columes = array('no', 'customer', 'content', 'created',
			'delivery','address_from','address_to','status','data');
	
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
		$rules['no'] 	= array('no', 'required|trim|xss_clean|callback__check_no');
		$rules['name'] 		= array('name', 'required|trim|xss_clean');
		$rules['created'] 	= array('created', 'required|trim|xss_clean');
		$rules['customer'] 		= array('customer', 'trim|xss_clean');
		$rules['content'] 		= array('content', 'required|trim');
		$this->form_validation->set_rules_params($params, $rules);
	}

	function _check_no($value)
	{
		$where['where']['no'] = $value;
		$where['where']['id !='] = $this->uri->rsegment(3);
		if(model(__CLASS__)->total($where) > 0)
		{
			$this->form_validation->set_message(__FUNCTION__, lang('notice_no_invalid'));
			return FALSE;
		}
		return true;
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

	private function _get_input(){
		$post = $this->input->post();

		$data = elements($this->columes, $post, '');
		$data['delivery'] = get_time_from_datetime($data['delivery']);
		$data['created'] = get_time_from_datetime($data['created']);
		$data['admin_id'] = admin_get_account_info()->id;
		// lay du lieu

		$tracking = array();
		if(isset($post['tracking_from'])){
			foreach($post['tracking_from'] as $key => $row){
				if(!$row && !$post['tracking_to'][$key]){
					continue;
				}
				$item = array();
				foreach(array('from','to','content','vehicle','reference','status','timestart','timeend') as $r) {
					$item['tracking_'.$r] = $post['tracking_'.$r][$key];
				}
				$item['tracking_timestart'] = get_time_from_datetime($item['tracking_timestart']);
				$item['tracking_timeend'] = get_time_from_datetime($item['tracking_timeend']);
				$tracking[] = $item;
			}
		}
		$data['data'] = $tracking;
		$this->saveSearch($data);
		$data['data'] = json_encode($data['data']);
		return $data;
	}
/*
 * ------------------------------------------------------
 *  Actions
 * ------------------------------------------------------
 */
	/**
	 * Them moi
	 */
	function add()
	{

		// Tai cac file thanh phan
		$this->load->library('form_validation');
		$this->load->helper('form');
		
		// Tu dong kiem tra gia tri cua 1 bien
		$param = $this->input->post('_autocheck');
		if ($param)
		{
			$this->_autocheck($param);
		}
		
		// Xu ly form
		if ($this->input->post('_submit'))
		{
			// Gan dieu kien cho cac bien
			$this->_set_rules($this->columes);
			
			// Xu ly du lieu
			$result = array();
			if ($this->form_validation->run())
			{
				$data = $this->_get_input();
				// Lay thong tin image

				$id = 0;
				$this->_model()->create($data, $id);

				
				// Khai bao du lieu tra ve
				$result['complete'] = TRUE;
				$result['location'] = admin_url(__CLASS__);
				set_message(lang('notice_add_success'));
			}
			
			// Neu du lieu khong phu hop
			if (empty($result['complete']))
			{
				foreach ($this->columes as $param)
				{
					$result[$param] = form_error($param);
				}
			}
			
			// Form output
			$this->_form_submit_output($result);
		}

		// Luu bien gui den view
		$this->data['action'] = current_url(true);
		
		// Hien thi view
		$this->_display('form');
	}
	
	/**
	 * Chinh sua
	 */
	function _edit($info)
	{
		// Tai cac file thanh phan
		$this->load->library('form_validation');
		$this->load->helper('form');

		// Tu dong kiem tra gia tri cua 1 bien
		$param = $this->input->post('_autocheck');
		if ($param)
		{
			$this->_autocheck($param);
		}
		
		// Xu ly form
		if ($this->input->post('_submit'))
		{
			// Gan dieu kien cho cac bien
			$this->_set_rules($this->columes);
			
			// Xu ly du lieu
			$result = array();
			if ($this->form_validation->run())
			{
				$data = $this->_get_input();
				$this->_model()->update($info->id, $data);

				// Khai bao du lieu tra ve
				$result['complete'] = TRUE;
				$result['location'] = admin_url(__CLASS__);
				set_message(lang('notice_update_success'));
			}
			
			// Neu du lieu khong phu hop
			if (empty($result['complete']))
			{
				foreach ($this->columes as $param)
				{
					$result[$param] = form_error($param);
				}
			}
			
			// Form output
			$this->_form_submit_output($result);
		}

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
 *  List handle
 * ------------------------------------------------------
 */
	/**
	 * Danh sach
	 */
	function index()
	{
		// Tai cac file thanh phan
		$this->load->helper('site');
		$this->load->helper('form');
		
		// Lay config
		$options = array();
		
		// Tao filter
		$filter_input 	= array();
		$filter_fields 	= array('no', 'customer', 'created', 'delivery', 'status');
		$filter = $this->_model()->filter_create($filter_fields, $filter_input);
		foreach($filter_fields as $f)
		{
			if(in_array($f,array('status'))){
				$v= $filter_input[$f] ;
				if($v =='on'){
					$filter[$f] =1;
				}
				elseif($v =='off'){
					$filter[$f] =0;
				}
			}
		}
		$this->data['filter'] = $filter_input;

		// Lay tong so
		$total = $this->_model()->filter_get_total($filter);
		$page_size = config('list_limit', 'main');
		$limit = $this->input->get('per_page');
		$limit = max(0, min($limit, get_limit_page_last($total, $page_size)));
		
		// Lay danh sach
		$input = array();
		$input['order'] = array(__CLASS__.'.id', 'desc');
		$input['limit'] = array($limit, $page_size);
		$list = $this->_model()->filter_get_list($filter, $input);
		
		$actions = array('edit', 'del', 'translate');
		$list = admin_url_create_option($list, strtolower(__CLASS__), 'id', $actions);
		foreach ($list as $row)
		{
			$row = $this->_mod()->add_info($row);
			$row = $this->_mod()->url($row);

			$row->_url_translate = admin_url("translate/table/".__CLASS__."/{$row->id}");

			$row->admin = admin_get_info($row->admin_id);
			foreach ($actions as $action)
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
		$pages_config = array();
		$pages_config['page_query_string'] = TRUE;
		$pages_config['base_url'] 	= current_url().'?'.url_build_query($filter_input);
		$pages_config['total_rows'] = $total;
		$pages_config['per_page'] 	= $page_size;
		$pages_config['cur_page'] 	= $limit;
		$this->data['pages_config'] = $pages_config;
		
		
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
	 * tim kiem
	 */
	function getaddress(){
		$term = $this->input->get('term');
		$where['where']['^name'] = $term;
		$item = array();
		foreach(model('trackingsearch')->select($where) as $row){
			$item['name'] = $row->name;
		}

		return_json($item);
	}

	private function saveSearch($data){
		foreach(array('customer', 'address_from','address_to') as $row){
			model('trackingsearch')->checkSearch($data[$row], $row);
		}
		foreach($data['data'] as $da) {
			foreach (array('tracking_from', 'tracking_to') as $row) {
				model('trackingsearch')->checkSearch($da[$row], $row);
			}
		}
	}
}