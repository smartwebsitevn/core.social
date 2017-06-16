<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cronjob extends MY_Controller {
	
	/**
	 * Ham khoi dong
	 */
	function __construct()
	{
		parent::__construct();
		
		// Tai cac file thanh phan
		$this->lang->load('admin/cronjob');
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
		$rules 					= array();
		$rules['title'] 		= array('title', 'required|trim|xss_clean');
		$rules['url'] 		= array('url', 'required|trim|xss_clean');
		$rules['status'] 	= array('meta_desc', 'required|trim|xss_clean');
		$rules['setting'] 		= array('meta_key', 'trim|xss_clean|callback__check_setting');
		$rules['desc'] 		= array('content', 'trim|xss_clean');
		$this->form_validation->set_rules_params($params, $rules);
	}
	/**
	 * Kiem tra setting
	 */
	function _check_setting()
	{
		$errors=false;
		$val = $this->_get_setting($errors);
		if ( $errors)
		{
			$megs= array();
			foreach($errors as $t){
				$megs[] =lang($t);
			}
			$megs =  implode(', ',$megs);
			$this->form_validation->set_message(__FUNCTION__, lang('notice_confirm_setting_invalid',$megs));
			return FALSE;
		}

		return TRUE;
	}

	/**
	 * Lay cac setting
	 */
	function _get_setting(&$error=null)
	{
		$inputs=array('day','hour',	'minute');
		$result = array();

		foreach ($inputs as $f) {

			$values =$this->input->post($f,true);
			if(!$values)	$error[]=$f;
			$result[$f] =$values;
		}

		return $result;

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
			$params = array('title','url', 'desc', 'setting', 'status');
			$this->_set_rules($params);

			// Xu ly du lieu
			$result = array();
			if ($this->form_validation->run())
			{
				// Lay input
				$setting = $this->_get_setting();
				$setting = serialize($setting);
				// Them du lieu vao data
				$data = array();
				$data['title'] 		= $this->input->post('title');
				$data['url'] 		= $this->input->post('url');
				$data['desc'] 	= handle_content($this->input->post('desc'), 'input');
				$data['status']	= $this->input->post('status');
				$data['setting']	= $setting;
				$data['created']	= now();

				// Lay id vua them
				$id = 0;
				$this->_model()->create($data, $id);

				// Khai bao du lieu tra ve
				$result['complete'] = TRUE;
				$result['location'] = admin_url('cronjob');
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



		// Luu cac bien gui den view
		$this->data['action'] = current_url();

		// Tao cay thu muc
	/*	$breadcrumbs = array();
		$breadcrumbs[] = array(admin_url('page'), lang('mod_page'));
		$breadcrumbs[] = array(current_url(), lang('add'));
		$this->data['breadcrumbs'] = $breadcrumbs;*/

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
			$params = array('title','url', 'desc', 'setting', 'status');

			$this->_set_rules($params);

			// Xu ly du lieu
			$result = array();
			if ($this->form_validation->run())
			{
				// Lay input
				$setting = $this->_get_setting();
				$setting = serialize($setting);
				// Them du lieu vao data
				$data = array();
				$data['title'] 		= $this->input->post('title');
				$data['url'] 		= $this->input->post('url');
				$data['desc'] 	= handle_content($this->input->post('desc'), 'input');
				$data['status']	= $this->input->post('status');
				$data['setting']	= $setting;
				$this->_model()->update($info->id,$data);

				// Khai bao du lieu tra ve
				$result['complete'] = TRUE;
				$result['location'] = admin_url('cronjob');
				set_message(lang('notice_edit_success'));
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


		// Luu bien gui den view
		$info->desc 	= handle_content($info->desc, 'output');
		$info->setting 	= unserialize($info->setting);
		$this->data['info'] = $info;

		// Luu cac bien gui den view
		$this->data['action'] = current_url();

		// Tao cay thu muc
		/*$breadcrumbs = array();
		$breadcrumbs[] = array(admin_url('page'), lang('mod_page'));
		$breadcrumbs[] = array(current_url(), lang('edit'));
		$this->data['breadcrumbs'] = $breadcrumbs;*/

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

		// Tai cac file thanh phan
		$this->load->helper('site');
		$this->load->helper('form');

		// Tao filter
		$filter_input 	= array();
		$filter_fields 	= array('id', 'status');
		$filter = $this->_model()->filter_create($filter_fields, $filter_input);
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

		$actions = array('edit', 'del');
		$list = admin_url_create_option($list, strtolower(__CLASS__), 'id', $actions);
		foreach ($list as $row)
		{
			$row->title = html_escape($row->title);
			$row->_created = get_date($row->created);

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
	
	
}