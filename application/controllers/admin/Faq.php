<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Faq extends MY_Controller {
	
	/**
	 * Ham khoi dong
	 */
	function __construct()
	{
		parent::__construct();
		
		// Tai cac file thanh phan
		$this->load->model('faq_model');
		$this->lang->load('admin/faq');
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
	function _set_rules($params = array())
	{
		$rules = array();
		$rules['cat_id'] 	= array('faq_cat', 'required|callback__check_faq_cat');
		$rules['question'] 		= array('question', 'required|trim|xss_clean');
		$rules['answer'] 		= array('answer', 'required|trim|xss_clean');
		$rules['sort_order'] 	= array('sort_order', 'is_natural');
		
		$this->form_validation->set_rules_params($params, $rules);
	}
	
	/**
	 * Kiem tra faq_cat
	 */
	function _check_faq_cat($value)
	{
		$this->load->model('faq_cat_model');
		
		$info = $this->faq_cat_model->get_info($value, 'id');
		if ( ! $info)
		{
			$this->form_validation->set_message(__FUNCTION__, lang('notice_value_not_exist'));
			return FALSE;
		}
		
		return TRUE;
	}
	
	/**
	 * Lay input
	 */
	protected function _get_input($param = '')
	{
	    $data = array();
	    foreach (array('question', 'answer', 'cat_id','status') as $p)
	    {
	        $data[$p] = $this->input->post($p);
	    }
        
	    $data['answer'] = handle_content($data['answer'], 'input');
		
	    if ($param)
	    {
	        return $data[$param];
	    }
        
	    return $data;
	}
	
	/**
	 * Tao data gui den view
	 *
	 * @param int $id
	 */
	protected function _create_view_data($id)
	{
		// Lay danh sach faq_cats
		$this->load->model('faq_cat_model');
		$this->data['cats'] = $this->faq_cat_model->get_list();
		
		// Khai bao cac bien cua widget upload images
		$widget_upload_files = array();
		$widget_upload_files['mod'] 		= 'multi';
		$widget_upload_files['file_type'] 	= 'image';
		$widget_upload_files['status'] 		= config('file_public', 'main');
		$widget_upload_files['table'] 		= 'faq';
		$widget_upload_files['table_id'] 	= $id;
		$widget_upload_files['table_field'] = 'files';
		$widget_upload_files['resize'] 		= FALSE;
		$widget_upload_files['thumb'] 		= FALSE;
		$this->data['widget_upload_files'] 	= $widget_upload_files;
		
		// Other
		$this->data['action'] = current_url();
	}
	
	
/*
 * ------------------------------------------------------
 *  Action handle
 * ------------------------------------------------------
 */
	/**
	 * Them moi
	 */
	function add()
	{
		// Tai cac file thanh phan
		$this->load->helper('form');
		$this->load->library('form_validation');
		
		// Tao fake id tam thoi de cap nhat cho file dinh kem
		$this->load->helper('file');
		$this->load->model('file_model');
		$fake_id = fake_id_get('faq');
		
		// Tu dong kiem tra gia tri cua 1 bien
		$param = $this->input->post('_autocheck');
		if ($param)
		{
			$this->_form_autocheck($param);
		}
		
		// Xu ly form
		if ($this->input->post('_submit'))
		{
			// Gan dieu kien cho cac bien
			$params = array('cat_id', 'question', 'answer');
			$this->_set_rules($params);
			
			// Xu ly du lieu
			$result = array();
			if ($this->form_validation->run())
			{
				// Cap nhat vao data
				$data = $this->_get_input();
				$data['sort_order'] = now();
				$data['created'] = now();

				$id = 0;
				$this->faq_model->create($data, $id);
				
				// Cap nhat lai table_id table file
				$this->model->file->update_table_id_of_mod('faq', $fake_id, $id);
				fake_id_del('faq');
				
				// Khai bao du lieu tra ve
				$result['complete'] = TRUE;
				$result['location'] = admin_url('faq').'?faq_cat='.$data['cat_id'];
				set_message(lang('notice_add_success'));
			}
			else
			{
				foreach ($params as $param)
				{
					$result[$param] = form_error($param);
				}
			}
			
			// Form output
			$this->_form_submit_output($result);
		}
		
		
		// View data
		$this->_create_view_data($fake_id);
		
		// Tao cay thu muc
		$breadcrumbs = array();
		$breadcrumbs[] = array(admin_url('faq'), lang('mod_faq'));
		$breadcrumbs[] = array(current_url(), lang('add'));
		$this->data['breadcrumbs'] = $breadcrumbs;
		
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
			$params = array('cat_id', 'question', 'answer', 'sort_order');
			$this->_set_rules($params);
			
			// Xu ly du lieu
			$result = array();
			if ($this->form_validation->run())
			{
				// Cap nhat du lieu vao data
				$data = $this->_get_input();
				$this->faq_model->update($info->id, $data);
				
				// Khai bao du lieu tra ve
				$result['complete'] = TRUE;
				$result['location'] = admin_url('faq').'?faq_cat='.$data['cat_id'];
				set_message(lang('notice_update_success'));
			}
			else
			{
				foreach ($params as $param)
				{
					$result[$param] = form_error($param);
				}
			}
			
			// Form output
			$this->_form_submit_output($result);
		}
		
		
		// Xu ly info
		$info = $this->_mod()->add_info($info);

		// Luu cac bien gui den view
		$this->data['info'] = $info;
		$this->_create_view_data($info->id);
		
		// Tao cay thu muc
		$breadcrumbs = array();
		$breadcrumbs[] = array(admin_url('faq'), lang('mod_faq'));
		$breadcrumbs[] = array(current_url(), lang('edit'));
		$this->data['breadcrumbs'] = $breadcrumbs;
		
		// Hien thi view
		$this->_display('form');
	}
	
	/**
	 * Xoa du lieu
	 */
	function _del($info)
	{
		// Thuc hien xoa
		$this->faq_model->del($info->id);

		$this->load->helper('file');
		file_del_table('faq', $info->id);
		
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
			$info = $this->faq_model->get_info($id);
			if ( ! $info) continue;
			
			// Kiem tra co the thuc hien hanh dong nay khong
			if ( ! $this->mod->faq->can_do($info, $action)) continue;
			
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
		// Cap nhat sort_order
		if ($this->input->get('act') == 'update_order')
		{
			$items = $this->input->post('items');
			$items = explode(',', $items);
			foreach ($items as $i => $id)
			{
				$this->faq_model->update_field($id, 'sort_order', $i+1);
			}
			
			$output = json_encode(array('complete' => TRUE));
			set_output('json', $output);
		}
		
		
		// Tai cac file thanh phan
		$this->load->helper('form');
		$this->load->model('faq_cat_model');
		
		// Tao filter
		$filter_input 	= array();
		$filter_fields 	= array('id', 'cat', 'question','status');
		$filter = $this->mod->faq->create_filter($filter_fields, $filter_input);
		$this->data['filter'] = $filter_input;
		
		// Lay tong so
		$total = $this->faq_model->filter_get_total($filter);
		$page_size = config('list_limit', 'main');
		$limit = $this->input->get('per_page');
		$limit = max(0, min($limit, get_limit_page_last($total, $page_size)));
		
		// Lay danh sach
		$input = array();
		$input['limit'] = array($limit, $page_size);
		$list = $this->faq_model->filter_get_list($filter, $input);
		
		$actions = array('edit', 'del', 'translate');
		$list = admin_url_create_option($list, strtolower(__CLASS__), 'id', $actions);
		foreach ($list as $row)
		{
			$row = $this->_mod()->add_info($row);
			$row->_cat = $this->faq_cat_model->get_info($row->cat_id);
			$row->_url_translate = admin_url("translate/table/faq/{$row->id}");
			
			foreach ($actions as $action)
			{
				$row->{'_can_'.$action} = ($this->mod->faq->can_do($row, $action) && admin_permission_url($row->{'_url_'.$action})) ? TRUE : FALSE;
			}
		}
		//pr($row);
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
		$this->data['cats'] = $this->faq_cat_model->get_list();
		$this->data['sort_url_update'] = current_url().'?act=update_order';
		
		// Breadcrumbs
		$breadcrumbs = array();
		$breadcrumbs[] = array(admin_url('faq'), lang('mod_faq'));
		$breadcrumbs[] = array(current_url(), lang('list'));
		$this->data['breadcrumbs'] = $breadcrumbs;
		
		// Hien thi view
		$this->_display();
	}
	
}