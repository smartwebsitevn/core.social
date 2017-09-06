<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Page extends MY_Controller {
	
	/**
	 * Ham khoi dong
	 */
	function __construct()
	{
		parent::__construct();
		
		// Tai cac file thanh phan
		$this->load->model('page_model');
		$this->lang->load('admin/page');
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
		$rules['title'] 		= array('title', 'required|trim|xss_clean');
		$rules['content'] 		= array('content', 'required|trim|xss_clean');
		$rules['description'] 	= array('description', 'trim|xss_clean');
		$rules['keywords'] 		= array('keywords', 'trim|xss_clean');
		
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

	private function _get_input(){

		$data = elements(array('title','intro','content','keywords','status',
				'url','description', 'keywords','titleweb','created',
				'nofollow','noindex','comment_status','public'),
				$this->input->post(), '');

		$data['status'] = config( ($data['status']) ? 'status_on' : 'status_off' , 'main');
		$data['intro'] = handle_content($data['intro'], 'input');
		$data['content'] = handle_content($data['content'], 'input');
		if(!$data['url'])
			$data['url'] = $data['title'];
		$data['url'] = convert_vi_to_en($data['url']);
		$data['created_day'] = get_time_from_date($data['created']);
		$data['admin_id'] = admin_get_account_info()->id;
		$data['updated'] = now();
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
		// Tao fake id tam thoi de cap nhat cho file dinh kem
		$fake_id = fake_id_get('page');
		
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
			$params = array('title', 'content', 'description', 'keywords');
			$this->_set_rules($params);
			
			// Xu ly du lieu
			$result = array();
			if ($this->form_validation->run())
			{
				$data = $this->_get_input();
				$data["created"] = now();
				if(!isset($data['sort_order']) || !$data['sort_order'])
					$data['sort_order'] = $this->_model()->get_total() + 1;
				// Lay id vua them
				$id = 0;
				$this->page_model->create($data, $id);
				model('tag')->_update_tag($id);
				// Cap nhat lai table_id cua image trong table file
				$this->load->model('file_model');
				$this->file_model->update_table_id_of_mod('page', $fake_id, $id);
				
				// Xoa fake_id
				fake_id_del('page');
				
				// Khai bao du lieu tra ve
				$result['complete'] = TRUE;
				$result['location'] = admin_url('page');
				set_message(lang('notice_add_success'));
			}
			
			// Neu du lieu khong phu hop
			if (empty($result['complete']))
			{
				foreach ($params as $param)
				{
					$result[$param] = form_error($param);
				}
			}
			
			// Form output
			$this->_form_submit_output($result);
		}
		
		
		// Khai bao cac bien cua widget upload images
		$widget_upload_files = array();
		$widget_upload_files['mod'] 		= 'multi';
		$widget_upload_files['file_type'] 	= 'file';
		$widget_upload_files['status'] 		= config('file_public', 'main');
		$widget_upload_files['table'] 		= 'page';
		$widget_upload_files['table_id'] 	= $fake_id;
		$widget_upload_files['table_field'] = 'files';
		$widget_upload_files['resize'] 		= FALSE;
		$widget_upload_files['thumb'] 		= FALSE;
		$this->data['widget_upload_files'] 	= $widget_upload_files;
		
		// Luu cac bien gui den view
		$this->data['action'] = current_url();
		
		// Tao cay thu muc
		$breadcrumbs = array();
		$breadcrumbs[] = array(admin_url('page'), lang('mod_page'));
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
			$params = array('title', 'content', 'description', 'keywords');
			$this->_set_rules($params);
			
			// Xu ly du lieu
			$result = array();
			if ($this->form_validation->run())
			{
				// Cap nhat du lieu vao data
				$data = $this->_get_input();
				$this->page_model->update($info->id, $data);
				model('tag')->_update_tag($info->id);
				// Khai bao du lieu tra ve
				$result['complete'] = TRUE;
				$result['location'] = admin_url('page');
				set_message(lang('notice_update_success'));
			}
			
			// Neu du lieu khong phu hop
			if (empty($result['complete']))
			{
				foreach ($params as $param)
				{
					$result[$param] = form_error($param);
				}
			}
			
			// Form output
			$this->_form_submit_output($result);
		}
		
		
		// Khai bao cac bien cua widget upload images
		$widget_upload_files = array();
		$widget_upload_files['mod'] 		= 'multi';
		$widget_upload_files['file_type'] 	= 'file';
		$widget_upload_files['status'] 		= config('file_public', 'main');
		$widget_upload_files['table'] 		= 'page';
		$widget_upload_files['table_id'] 	= $info->id;
		$widget_upload_files['table_field'] = 'files';
		$widget_upload_files['resize'] 		= FALSE;
		$widget_upload_files['thumb'] 		= FALSE;
		$this->data['widget_upload_files'] 	= $widget_upload_files;
		
		// Luu bien gui den view
		$info->title 	= html_escape($info->title);
		$info->content 	= handle_content($info->content, 'output');
		$this->data['info'] = $info;
		
		// Luu cac bien gui den view
		$this->data['action'] = current_url();
		
		// Tao cay thu muc
		$breadcrumbs = array();
		$breadcrumbs[] = array(admin_url('page'), lang('mod_page'));
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
		$this->page_model->del($info->id);
		model('tag')->_update_tag($info->id);
		// Xoa file
		$this->load->helper('file');
		file_del_table('page', $info->id);
		
		// Gui thong bao
		set_message(lang('notice_del_success'));
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
		if ($this->input->get('act') == 'sort_update')
		{
			$items = $this->input->post('items');
			$items = explode(',', $items);

			foreach ($items as $i => $id)
			{
				$data = array();
				$data['sort_order']	= $i;
				$this->_model()->update($id, $data);
			}

			$output = json_encode(array('complete' => TRUE));
			set_output('json', $output);
		}
		// Tai cac file thanh phan
		$this->load->helper('site');
		$this->load->helper('form');
		
		// Tao filter
		$filter_input 	= array();
		$filter_fields 	= array('id', 'title','status', 'created', 'created_to');
		$filter = $this->page_model->filter_create($filter_fields, $filter_input);
		$this->data['filter'] = $filter_input;
		
		// Lay tong so
		$total = $this->page_model->filter_get_total($filter);
		$page_size = config('list_limit', 'main');
		$limit = $this->input->get('per_page');
		$limit = max(0, min($limit, get_limit_page_last($total, $page_size)));
		
		// Lay danh sach
		$input = array();
		$input['limit'] = array($limit, $page_size);
		$list = $this->page_model->filter_get_list($filter, $input);
		
		$actions = array('edit', 'del', 'translate');
		$list = admin_url_create_option($list, strtolower(__CLASS__), 'id', $actions);
		foreach ($list as $row)
		{
			$row = $this->_mod()->url($row);
			$row = $this->_mod()->url_lang($row, strtolower(__CLASS__));
			$row->title = html_escape($row->title);
			$row->_created = get_date($row->created);
			$row->_url_translate = admin_url("translate/table/page/{$row->id}");
			$row->_updated_time = get_date($row->updated, 'time');
			$row->admin = admin_get_info($row->admin_id);
			foreach ($actions as $action)
			{
				$row->{'_can_'.$action} = ($this->_mod()->can_do($row, $action) && admin_permission_url($row->{'_url_'.$action})) ? TRUE : FALSE;
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
			//if ( ! admin_permission_url($url)) continue;
			
			$actions[$v] = $url;
		}
		$this->data['actions'] = $actions;
		
		// Luu bien gui den view
		$this->data['action'] 	= current_url();
		$this->data['sort_url_update'] = current_url() . '?act=sort_update';
		// Breadcrumbs
		/*$breadcrumbs = array();
		$breadcrumbs[] = array(admin_url('page'), lang('mod_page'));
		$breadcrumbs[] = array(current_url(), lang('list'));
		$this->data['breadcrumbs'] = $breadcrumbs;*/
		
		// Hien thi view
		$this->_display();
	}

	/*
     * ------------------------------------------------------
     *  List handle
     * ------------------------------------------------------
     */
	/**
	 * Danh sach
	 */
	function menu_callback()
	{
		// Tai cac file thanh phan
		$this->load->helper('site');
		$this->load->helper('form');

		// Tao filter
		$filter_input 	= array();
		$filter_fields 	= array('id', 'title','status', 'created', 'created_to');
		$filter = $this->page_model->filter_create($filter_fields, $filter_input);
		$this->data['filter'] = $filter_input;

		// Lay tong so
		$total = $this->page_model->filter_get_total($filter);
		$page_size = config('list_limit', 'main');
		$limit = $this->input->get('per_page');
		$limit = max(0, min($limit, get_limit_page_last($total, $page_size)));

		// Lay danh sach
		$input = array();
		$input['limit'] = array($limit, $page_size);
		$list = $this->page_model->filter_get_list($filter, $input);

		foreach ($list as $row)
		{
			$row = $this->_mod()->url($row);
			$row->title = html_escape($row->title);
			$row->_created = get_date($row->created);
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


		// Luu bien gui den view
		$this->data['action'] 	= current_url();
		// Hien thi view
		$this->_display('menu_callback',null);
	}

}