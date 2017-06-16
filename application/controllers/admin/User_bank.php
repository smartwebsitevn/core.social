<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_bank extends MY_Controller
{
	/**
	 * Ham khoi dong
	 */
	public function __construct()
	{
		parent::__construct();

		$this->lang->load('site/user_bank');
	}

	/**
	 * Remap method
	 */
	function _remap($method)
	{
	    if (in_array($method, array(
	        'active', 'del', 'cancel'
	    )))
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
	 * Thuc hien tuy chinh
	 */
	function _action($action)
	{
	    // Lay input
	    $ids = $this->uri->rsegment(3);
	    $ids = (!$ids) ? $this->input->post('id') : $ids;
	    $ids = (!is_array($ids)) ? array($ids) : $ids;
	
	    // Thuc hien action
	    foreach ($ids as $id)
	    {
	        // Xu ly id
	        $id = (!is_numeric($id)) ? 0 : $id;
	        	
	        // Kiem tra id
	        $info = model('user_bank')->get_info($id);
	        if (!$info) continue;
	        
	        // Chuyen den ham duoc yeu cau
	        $this->{'_'.$action}($info);
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
	    $this->load->helper('form');
	    
	    // Lay config
	    $order_statuss = array('completed', 'pending', 'canceled');
	
	
	    // Lay gia tri cua filter dau vao
	    $filter_input = array();
	    $filter_fields = array('id', 'user', 'bank', 'status', 'created', 'created_to');
	    foreach ($filter_fields as $f)
	    {
	        $v = $this->input->get($f);
	        	
	        if (
	            $f == 'status' && !in_array($v, $order_statuss)
	        )
	        {
	            $v = '';
	        }
	        	
	        $filter_input[$f] = $v;
	    }
	
	    if ($filter_input['id'])
	    {
	        foreach ($filter_input as $f => $v)
	        {
	            $filter_input[$f] = ($f != 'id') ? '' : $v;
	        }
	    }
	    $this->data['filter'] = $filter_input;
	
	    // Tao bien filter
	    $filter = array();
	    foreach ($filter_input as $f => $v)
	    {
	        if (!strlen($v)) continue;
	        	
	        switch ($f)
	        {
	            case 'created':
	                {
	                    $created_to = $filter_input['created_to'];
	                    $v = (strlen($created_to)) ? array($v, $created_to) : $v;
	                    $v = get_time_between($v);
	                    $v = (!$v) ? NULL : $v;
	                    break;
	                } 
	            case 'status':
                    {
                        $v = array_search($v, $order_statuss);
                        break;
                    }     
                case 'user':
                    {
                        $this->load->model('user_model');
                        $user = $this->user_model->get_info_rule(array('id' => $v));
                        if(!$user)
                        {
                             $user = $this->user_model->get_info_rule(array('email' => $v));
                        }
                        if(!$user)
                        {
                            $user = $this->user_model->get_info_rule(array('phone' => $v));
                        }
                        if(!$user)
                        {
                            $user = $this->user_model->get_info_rule(array('username' => $v));
                        }
                        $v = isset($user->id) ? $user->id : 0;
                        break;
                    }   
	        }
	        	
	        if ($v === NULL) continue;
	        	
	        $filter[$f] = $v;
	    }
	
	
	    // Lay tong so
	    $total = model('user_bank')->filter_get_total($filter);
	    $page_size = config('list_limit', 'main');
	    $limit = $this->input->get('per_page');
	    $limit = min($limit, $total-fmod($total,$page_size));
	    $limit = max(0, $limit);
	
	    // Lay danh sach
	    $input = array();
	    $input['limit'] = array($limit, $page_size);
	    $list = model('user_bank')->filter_get_list($filter, $input);
	
	    $actions = array('del', 'active', 'cancel');
	    $list = admin_url_create_option($list, 'user_bank', 'id', $actions);
	    foreach ($list as $row)
	    {
	        $row->bank = model('bank')->get_info($row->bank_id, 'name');
	        $row->city = model('city')->get_info($row->city_id, 'name');
	    }
	    $this->data['list'] = $list;
	
	
	    // Tao query chia trang
	    $pages_query = array();
	    foreach ($filter_input as $f => $v)
	    {
	        if (!strlen($v)) continue;
	        $pages_query[$f] = $v;
	    }
	    $pages_query = http_build_query($pages_query);
	
	    // Tao chia trang
	    $pages_config = array();
	    $pages_config['page_query_string'] = TRUE;
	    $pages_config['base_url'] 	= admin_url('user').'?'.$pages_query;
	    $pages_config['total_rows'] = $total;
	    $pages_config['per_page'] 	= $page_size;
	    $pages_config['cur_page'] 	= $limit;
	    $this->data['pages_config'] = $pages_config;
	
	    // Tao action list
	    $actions = array();
	    foreach (array('active', 'cancel', 'del') as $v)
	    {
	        $url = admin_url('user_bank/'.$v);
	        $actions[$v] = $url;
	    }
	    $this->data['actions'] = $actions;
	
	    // Luu bien gui den view
	    $this->data['action'] 			= current_url();
	    $this->data['order_statuss'] 	= $order_statuss;
	    $this->data['banks'] 	= model('bank')->get_list();
	     
	    // Hien thi view
	    $this->_display();
	}
	

	/**
	 * Chap nhan thong tin xac thuc
	 */
	function _active($info)
	{
	    // Gan trang thai xac thuc
	    $data = array();
	    $data['status'] = mod('order')->status('completed');
	
	    // Cap nhat du lieu vao data
	    model('user_bank')->update($info->id, $data);
	    // Gui thong bao
	    set_message(lang('notice_update_success'));
	    return TRUE;
	}
	
	/**
	 * Chap nhan thong tin xac thuc
	 */
	function _cancel($info)
	{
	    // Gan trang thai xac thuc
	    $data = array();
	    $data['status'] = mod('order')->status('canceled');
	
	    // Cap nhat du lieu vao data
	    model('user_bank')->update($info->id, $data);
	    // Gui thong bao
	    set_message(lang('notice_update_success'));
	    return TRUE;
	}
	
	/**
	 * Xoa du lieu
	 */
	function _del($info)
	{
	    // Thuc hien xoa
	    model('user_bank')->del($info->id);
	
	    // Gui thong bao
	    set_message(lang('notice_del_success'));
	}
	
}

