<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Message extends MY_Controller {
	
	/**
	 * Ham khoi dong
	 */
	function __construct()
	{
		parent::__construct();
		
		// Tai cac file thanh phan
		$this->lang->load('admin/message');
		// Tai cac file thanh phan
		$this->load->helper('message');
		
		$this->load->model('message_receive_model');
		$this->load->model('message_model');
		
		
	}
	
	/**
	 * Remap method
	 */
	function _remap($method)
	{
		if (in_array($method, array('del', 'view')))
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
  *  Action handle
  * ------------------------------------------------------
  */
	/**
	 * Gan dieu kien cho cac bien
	 */
	function _set_rules($params = array())
	{
		if (!is_array($params)) {
			$params = array($params);
		}

		$rules = array();
		$rules['reply'] = array('reply', 'required|trim|xss_clean');
		$this->form_validation->set_rules_params($params, $rules);
	}
/*
 * ------------------------------------------------------
 *  Rule handle
 * ------------------------------------------------------
 */

	/**
	 * Xoa du lieu
	 */
	function _del($info)
	{
		// Thuc hien xoa
		$this->_model()->del($info->id);
		model('message_receive')->del_rule(array('message_id' => $info->id));
		
		// Gui thong bao
		set_message(lang('notice_del_success'));
	}
	

	/**
	 * Xem tin nhan
	 */
	function _view($message)
	{

		$message = $this->_mod()->add_info($message);
		$message = $this->_mod()->url($message);

		$user = model("user")->get_info($message->user_id, 'username, name, email, phone');
		$message->user = $user;

		$_input = array(
			'limit' => array(0, 3),
			'where' => array('message_id' => $message->id)
		);
		$receives = model("message_receive")->get_list_receive($_input);
		$message->receives = $receives;


		if($message->user_execute)
		{
			$user_execute = model('user')->get_info($message->user_execute, 'username');
			$message->user_execute = $user_execute;
		}


	    $this->data['message'] = $message;
	
	    //lấy tất cả người nhận
	    $this->load->model('message_receive_model');
	    $_input = array(
	        'where' => array('message_id' => $message->id)
	    );
	    $receives = $this->message_receive_model->get_list_receive($_input);
	    $this->data['receives'] = $receives;


		//cap nhat da xem
		if(!$message->admin_readed){
		$data = array(
			'admin_readed' => 1,
			'admin_readed_time' => now(),
		);
		model('message')->update($message->id, $data);
		}
	    view('admin/message/view', $this->data);
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
	/**
	 * Kiem tra co the thuc hien hanh dong hay khong
	 */
	function _can_do($info, $action)
	{
		switch ($action)
		{
			case 'view':
			case 'del':
			{
				return TRUE;
			}
		}

		return FALSE;
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
		$this->load->model('message_receive_model');
		$this->load->model('user_model');


		$this->load->library('form_validation');
		$this->load->helper('form');
		// Tai cac file thanh phan
		$this->data['user'] = user_get_account_info();
		// Xu ly form
		if ($this->input->post('_submit')) {
			$id= $this->input->post('id');
			$info=$this->_model()->get_info($id);
			if(!$info || $info->admin_replyed)
				return false;
			// Gan dieu kien cho cac bien
			$params = array('reply');
			$this->_set_rules($params);
			// Xu ly du lieu
			$result = array();
			if ($this->form_validation->run()) {
				// Them du lieu vao data
				$data = array();
				$data ['admin_replyed'] = 1;
				$data ['admin_replyed_content'] = $this->input->post('reply');
				$data['admin_replyed_time'] = now();
				$this->_model()->update($id,$data);
				set_message(lang('notice_request_success'));
				// Khai bao du lieu tra ve
				$result['complete'] = TRUE;
				$result['reload'] = 1;
			} else {
				foreach ($params as $param) {
					$result[$param] = form_error($param);
				}

			}

			$output = json_encode($result);
			set_output('json', $output);
		}

		// Lay config
		$types = array('send_admin', 'is_spam');
		$this->data['types'] = $types;
		$this->data['status_readed'] 	=  array('unreaded', 'readed');

		// Tao filter
		$filter_input 	= array();
		$filter_fields 	= array('id', 'title', 'admin_readed','created', 'created_to', 'user', 'type');
		$filter = $this->_model()->filter_create($filter_fields, $filter_input);
		if(isset($filter['type']) && $filter['type'] != '' && in_array($filter['type'], $types))
		{
		    $filter[$filter['type']] = true;
		}
		if(isset($filter['user']) && $filter['user'] != '')
		{
		    $username = trim(strtolower($filter['user']));
		    $user = model('user')->get_info_rule(array('username' => $username), 'id');
		    $filter['user'] = isset($user->id) ? $user->id : 0;
		}
		
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
		
		$actions = array('view','del');
		$list = admin_url_create_option($list, strtolower(__CLASS__), 'id', $actions);

		foreach ($list as $row)
		{
			$row = $this->_mod()->add_info($row);
			$row = $this->_mod()->url($row);
			
			$user = $this->user_model->get_info($row->user_id, 'username, name, email, phone');
			$row->user = $user;
			
			$_input = array(
			    'limit' => array(0, 3),
			    'where' => array('message_id' => $row->id)
			);
			$receives = $this->message_receive_model->get_list_receive($_input);
			$row->receives = $receives;
			

			if($row->user_execute)
			{
			    $user_execute = model('user')->get_info($row->user_execute, 'username');
			    $row->user_execute = $user_execute;
			}
			foreach ($actions as $action)
			{
				$row->{'_can_'.$action} = ($this->_can_do($row, $action) && admin_permission_url($row->{'_url_'.$action})) ? TRUE : FALSE;
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
		$this->data['url_search_username'] = admin_url('user/ac/username');
		
		// Luu bien gui den view
		$this->data['action'] 	= current_url();
		
		// Hien thi view
		$this->_display();
	}
	
}