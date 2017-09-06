<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Question extends MY_Controller {
	
	function __construct()
	{
		parent::__construct();
		
		// Tai cac file thanh phan
		$this->load->model('question_model');
		$this->lang->load('admin/question');
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
		if (!is_array($params))
		{
			$params = array($params);
		}
		
		$rules = array();
		$rules['title'] 		= array('title', 'required|trim|xss_clean');
		$rules['content'] 		= array('content', 'required|trim|xss_clean');
		$rules['meta_desc'] 	= array('meta_desc', 'trim|xss_clean');
		$rules['meta_key'] 		= array('meta_key', 'trim|xss_clean');
		
		foreach ($params as $param)
		{
			if (isset($rules[$param]))
			{
				$this->form_validation->set_rules($param, 'lang:'.$rules[$param][0], $rules[$param][1]);
			}
		}
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
		
		// Tao fake id tam thoi de cap nhat cho file dinh kem
		$this->load->helper('file');
		$fake_id = file_fake_id_get('question');
		
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
			$params = array('title', 'content');
			$this->_set_rules($params);
			
			// Xu ly du lieu
			$result = array();
			if ($this->form_validation->run())
			{
				// Lay input
				$content = $this->input->post('content');
				$content = handle_content($content, 'input');
				
				// Them du lieu vao data
				$data = array();	
				$data['title'] 		= $this->input->post('title');
				$data['content'] 	= $content;
				$data['sort_order'] = $this->input->post('sort_order');
				$data['created']	= now();
				
				// Lay question_id vua them
				$question_id = $this->db->insert_id();
				$this->question_model->create($data, $question_id);
				
				// Cap nhat lai table_id cua image trong table file
				$this->load->model('file_model');
				$this->file_model->update_table_id_of_mod('question-images', $fake_id, $question_id);
				
				// Xoa fake_id
				file_fake_id_del('question');
				
				// Khai bao du lieu tra ve
				$result['complete'] = TRUE;
				$result['location'] = admin_url('question');
				$this->session->set_flashdata('flash_message', array('success', lang('notice_add_success')));
			}
			else
			{
				foreach ($params as $param)
				{
					$result[$param] = form_error($param);
				}
			}
			
			$output = json_encode($result);
			set_output('json', $output);
		}
		
		
		// Khai bao cac bien cua widget upload images
		$widget_upload_images = array();
		$widget_upload_images['mod'] 		= 'multi';
		$widget_upload_images['file_type'] 	= 'image';
		$widget_upload_images['status'] 	= config('file_public', 'main');
		$widget_upload_images['table'] 		= 'question-images';
		$widget_upload_images['table_id'] 	= $fake_id;
		$widget_upload_images['resize'] 	= FALSE;
		$widget_upload_images['thumb'] 		= FALSE;
		$this->data['widget_upload_images'] = $widget_upload_images;
		
		// Tao cay thu muc
		$breadcrumbs = array();
		$breadcrumbs[] = array(admin_url('question'), lang('mod_question'));
		$breadcrumbs[] = array('', lang('add'));
		$this->data['breadcrumbs'] = $breadcrumbs;
		
		// Hien thi view
		$this->data['temp'] = 'admin/question/add';
		$this->load->view('admin/main', $this->data);
	}
	
	/**
	 * Chinh sua
	 */
	function _edit($question)
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
			$params = array('title', 'content');
			$this->_set_rules($params);
			
			// Xu ly du lieu
			$result = array();
			if ($this->form_validation->run())
			{
				// Lay input
				$content = $this->input->post('content');
				$content = handle_content($content, 'input');
				
				// Cap nhat du lieu vao data
				$data = array();	
				$data['title'] 		= $this->input->post('title');
				$data['sort_order'] = $this->input->post('sort_order');
				$data['content'] 	= $content;
				
				$this->question_model->update($question->id, $data);
				
				// Khai bao du lieu tra ve
				$result['complete'] = TRUE;
				$result['location'] = admin_url('question');
				$this->session->set_flashdata('flash_message', array('success', lang('notice_update_success')));
			}
			else
			{
				foreach ($params as $param)
				{
					$result[$param] = form_error($param);
				}
			}
			
			$output = json_encode($result);
			set_output('json', $output);
		}
		
		
		// Khai bao cac bien cua widget upload images
		$widget_upload_images = array();
		$widget_upload_images['mod'] 		= 'multi';
		$widget_upload_images['file_type'] 	= 'image';
		$widget_upload_images['status'] 	= config('file_public', 'main');
		$widget_upload_images['table'] 		= 'question-images';
		$widget_upload_images['table_id'] 	= $question->id;
		$widget_upload_images['resize'] 	= FALSE;
		$widget_upload_images['thumb'] 		= FALSE;
		$this->data['widget_upload_images'] = $widget_upload_images;
		
		// Luu bien gui den view
		$question->title 	= html_escape($question->title);
		$question->content 	= handle_content($question->content, 'output');
		$this->data['question'] = $question;
		
		// Luu cac bien gui den view
		$this->data['action'] = current_url();
		
		// Tao cay thu muc
		$breadcrumbs = array();
		$breadcrumbs[] = array(admin_url('question'), lang('mod_question'));
		$breadcrumbs[] = array('', lang('edit'));
		$this->data['breadcrumbs'] = $breadcrumbs;
		
		// Hien thi view
		$this->data['temp'] = 'admin/question/edit';
		$this->load->view('admin/main', $this->data);
	}
	
	/**
	 * Xoa du lieu
	 */
	function _del($question)
	{
		// Thuc hien xoa
		$this->question_model->del($question->id);
		
		// Xoa file
		$this->load->helper('file');
		file_del_group('question-images', $question->id);
		
		// Gui thong bao
		$this->session->set_flashdata('flash_message', array('success', lang('notice_del_success')));
	}
	
	
/*
 * ------------------------------------------------------
 *  Action handle
 * ------------------------------------------------------
 */
	/**
	 * Thuc hien tuy chinh
	 */
	function action()
	{
		// Lay input
		$action = $this->uri->rsegment(3);
		$id = $this->uri->rsegment(4);
		$id = (!is_numeric($id)) ? 0 : $id;
		
		// Xu ly thuc hien action list
		if ($action == '_list')
		{
			$action = $this->input->post('action');
			$ids 	= $this->input->post('ids');
			$this->_action_list($action, $ids);
		}
		
		// Kiem tra id
		$question = $this->question_model->get_info($id);
		if (!$question)
		{
			$this->session->set_flashdata('flash_message', array('warning', lang('notice_page_not_found')));
			redirect_admin('question');
		}
		
		// Chuyen den ham duoc yeu cau
		$this->{'_'.$action}($question);
	}
	
	/**
	 * Thuc hien hanh dong voi danh sach
	 */
	function _action_list($action, $ids)
	{
		$result = array();
		
		// Thuc hien hanh dong
		$ids = (!is_array($ids)) ? array() : $ids;
		foreach ($ids as $id)
		{
			// Lay thong tin
			$question = $this->question_model->get_info($id);
			if (!$question)
			{
				continue;
			}
			
			// Chuyen den ham duoc yeu cau
			$this->{'_'.$action}($question);
		}
		
		// Khai bao du lieu tra ve
		if (count($ids))
		{
			$result['complete'] = TRUE;
		}
		
		$output = json_encode($result);
		set_output('json', $output);
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
		
		// Lay gia tri cua filter dau vao
		$filter_input = array();
		$filter_fields = array('id');
		foreach ($filter_fields as $f)
		{
			$v = $this->input->get($f);
			
			$filter_input[$f] = $v;
		}
		$this->data['filter'] = $filter_input;
		
		// Tao bien filter
		$filter = array();
		foreach ($filter_input as $f => $v)
		{
			if (!strlen($v)) continue;
			
			if ($v === NULL) continue;
			
			$filter[$f] = $v;
		}
		
		// Lay tong so
		$total = $this->question_model->filter_get_total($filter);
		$page_size = config('list_limit', 'main');
		$limit = $this->input->get('per_page');
		$limit = min($limit, get_limit_page_last($total, $page_size));
		$limit = max(0, $limit);
		
		// Lay danh sach
		$input = array();
		$input['limit'] = array($limit, $page_size);
		$list = $this->question_model->filter_get_list($filter, $input);
		
		$actions = array('edit', 'del');
		foreach ($list as $row)
		{
			$row = site_create_url('question', $row);
			$row->title = html_escape($row->title);
			$row->_created = get_date($row->created);
		}
		$list = admin_url_create_option($list, __CLASS__.'/action', 'id', $actions);
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
		$pages_config['base_url'] 	= admin_url('question').'?'.$pages_query;
		$pages_config['total_rows'] = $total;
		$pages_config['per_page'] 	= $page_size;
		$pages_config['cur_page'] 	= $limit;
		$this->data['pages_config'] = $pages_config;
		
		
		// Luu bien gui den view
		$this->data['action'] = current_url();
		$this->data['actions'] = array('del');
		$this->data['actions_url'] = admin_url('question/action/_list');
		
		// Breadcrumbs
		$breadcrumbs = array();
		$breadcrumbs[] = array(admin_url('question'), lang('mod_question'));
		$breadcrumbs[] = array('', lang('list'));
		$this->data['breadcrumbs'] = $breadcrumbs;
		
		// Message
		$message = $this->session->flashdata('flash_message');
		if ($message)
		{
			$this->data['message'] = $message;
		}
		
		// Hien thi view
		$this->data['temp'] = 'admin/question/index';
		$this->load->view('admin/main', $this->data);
	}
	
}