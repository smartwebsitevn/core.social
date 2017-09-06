<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Video extends MY_Controller {

	/**
	 * Ham khoi dong
	 */
	function __construct()
	{
		parent::__construct();

		// Tai cac file thanh phan
		$this->lang->load('admin/'.__CLASS__);
		$this->data['class'] = __CLASS__;
		$this->lang->load('form');
		$this->load->helper('currency');
	}

	public $columes = array('name','summary', 'content',
			'order','created','feature','status','video','lang_id',
			'url','description', 'keywords','titleweb',
			'comment_status','nofollow','public');

	/**
	 * Remap method
	 */
	function _remap($method)
	{
		if (in_array($method, array('edit', 'del', 'feature', 'feature_del')))
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
		$rules['video'] 	= array('video', 'required|trim|xss_clean|callback__check_video');
		$rules['name'] 		= array('name', 'required|trim|xss_clean');
		$rules['created'] 	= array('created', 'required|trim|xss_clean');
		$rules['content'] 		= array('content', 'trim');
		$rules['description'] 	= array('description', 'trim|xss_clean');
		$rules['keywords'] 		= array('keywords', 'trim|xss_clean');
		$rules['lang_id'] 		= array('lang_id', 'required|trim|xss_clean');
		//$rules['image'] 		= array('image', 'callback__check_image');
		$rules['url'] 			= array('url', 'trim|xss_clean');
		$this->form_validation->set_rules_params($params, $rules);
	}

	function _check_video($value){
		if(!$value)
			return true;

		$this->load->helper('youtube');
		$value = preg_replace('#<br\s*/?>#i', "\n", $value);
		foreach(explode("\n", $value) as $row)
		{
			if(getIdYouTube($row) === false)
			{
				$this->form_validation->set_message(__FUNCTION__, lang('error_link_youtube', ['url' => $row]));
				return false;
			}
		}
		return true;
	}

	/**
	 * Kiem tra image
	 */
	function _check_image()
	{
		$id 	= ($this->uri->rsegment(2) == 'add') ? fake_id_get(__CLASS__) : $this->uri->rsegment(3);
		$image 	= $this->_get_image($id);

		if ( ! $image)
		{
			$this->form_validation->set_message(__FUNCTION__, lang('required'));
			return FALSE;
		}

		return TRUE;
	}

	/**
	 * Lay image
	 */
	function _get_image($id)
	{
		$this->load->model('file_model');
		$image = $this->file_model->get_info_of_mod(__CLASS__, $id, 'image', 'id, file_name');

		return $image;
	}

	/**
	 * Cap nhat image
	 */
	function _update_image($id)
	{
		// Lay thong tin cua file
		$file = $this->_get_image($id);
		if ( ! $file)
		{
			$file = new stdClass();
			$file->id = 0;
			$file->file_name = '';
		}

		// Cap nhat du lieu vao data
		$data = array();
		$data['image_id']	= $file->id;
		$data['image_name']	= $file->file_name;
		$this->_model()->update($id, $data);
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

	private function _get_input($info = null){
		$data = elements($this->columes, $this->input->post(), '');
		$data['content'] = handle_content($data['content'], 'input');
		if(!$data['url'])
			$data['url'] = $data['name'];

		$data['url'] = $this->getUrl(conver_url($data['url']), $info ? $info->id : 0);

		$data['created'] = get_time_from_date($data['created']);
		$data['admin_id'] = admin_get_account_info()->id;
		$data['updated'] = now();

		if($data['video']) {
			$data['video'] = preg_replace('#<br\s*/?>#i', "\n", $data['video']);
			$data['video'] = json_encode(explode("\n", $data['video']));
		}
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
		$fake_id = fake_id_get(__CLASS__);

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
				$image = $this->_get_image($fake_id);
				if ($image)
				{
					$data['image_id']	= $image->id;
					$data['image_name']	= $image->file_name;
				}
				$id = 0;
				$this->_model()->create($data, $id);
				model('tag')->_update_tag($id);
				// Cap nhat lai table_id cua image trong table file
				$this->file_model->update_table_id_of_mod(__CLASS__, $fake_id, $id);

				// Xoa fake_id
				fake_id_del(__CLASS__);

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


		// Khai bao cac bien cua widget upload
		$widget_upload = array();
		$widget_upload['mod'] 			= 'single';
		$widget_upload['file_type'] 	= 'image';
		$widget_upload['status'] 		= config('file_public', 'main');
		$widget_upload['table'] 		= __CLASS__;
		$widget_upload['table_id'] 		= $fake_id;
		$widget_upload['table_field'] 	= 'image';
		$widget_upload['resize'] 		= TRUE;
		$widget_upload['thumb'] 		= TRUE;
		$widget_upload['thumb_width'] 		= 185;
		$widget_upload['thumb_height'] 		= 165;
		$this->data['widget_upload'] 	= $widget_upload;

		// Khai bao cac bien cua widget upload images
		$widget_upload_files = array();
		$widget_upload_files['mod'] 		= 'multi';
		$widget_upload_files['file_type'] 	= 'image';
		$widget_upload_files['status'] 		= config('file_public', 'main');
		$widget_upload_files['table'] 		= __CLASS__;
		$widget_upload_files['table_id'] 	= $fake_id;
		$widget_upload_files['table_field'] = 'files';
		$widget_upload_files['resize'] 		= FALSE;
		$widget_upload_files['thumb'] 		= FALSE;
		$this->data['widget_upload_files'] 	= $widget_upload_files;

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

		// Cap nhat thong tin
		if ($this->input->get('act') == 'update_image')
		{
			$this->_update_image($info->id);
			exit();
		}

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
				$data = $this->_get_input($info);
				$this->_model()->update($info->id, $data);
				// Lay anh dai dien
				$this->_update_image($info->id);
				model('tag')->_update_tag($info->id);
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


		// Khai bao cac bien cua widget upload
		$widget_upload = array();
		$widget_upload['mod'] 			= 'single';
		$widget_upload['file_type'] 	= 'image';
		$widget_upload['status'] 		= config('file_public', 'main');
		$widget_upload['table'] 		= __CLASS__;
		$widget_upload['table_id'] 		= $info->id;
		$widget_upload['table_field'] 	= 'image';
		$widget_upload['resize'] 		= TRUE;
		$widget_upload['thumb'] 		= TRUE;
		$widget_upload['url_update']	= current_url().'?act=update_image';
		$this->data['widget_upload']	= $widget_upload;

		// Khai bao cac bien cua widget upload images
		$widget_upload_files = array();
		$widget_upload_files['mod'] 		= 'multi';
		$widget_upload_files['file_type'] 	= 'image';
		$widget_upload_files['status'] 		= config('file_public', 'main');
		$widget_upload_files['table'] 		= __CLASS__;
		$widget_upload_files['table_id'] 	= $info->id;
		$widget_upload_files['table_field'] = 'files';
		$widget_upload_files['resize'] 		= FALSE;
		$widget_upload_files['thumb'] 		= true;
		$widget_upload_files['thumb_width'] 		= 150;
		$widget_upload_files['thumb_height'] 		= 150;
		$this->data['widget_upload_files'] 	= $widget_upload_files;


		// Xu ly thong tin
		$info = $this->_mod()->add_info($info);

		if($info->video)
			$info->video = implode("\n", json_decode($info->video));
		else
			$info->video = '';
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
		model('tag')->_update_tag($info->id);
		// Xoa file
		$this->load->helper('file');
		file_del_table(__CLASS__, $info->id);

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
		$options = array('feature');

		// Tao filter
		$filter_input 	= array();
		$filter_fields 	= array('id', 'name','lang_id', 'created', 'created_to', 'feature','status');
		$filter = $this->_model()->filter_create($filter_fields, $filter_input);
		foreach($filter_fields as $f)
		{
			if(in_array($f,array('feature', 'status'))){
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

		$actions = array('edit', 'del', 'feature', 'feature_del', 'translate');
		$list = admin_url_create_option($list, strtolower(__CLASS__), 'id', $actions);
		foreach ($list as $row)
		{
			$row = $this->_mod()->add_info($row);
			$row = $this->_mod()->url($row);

			$row->language = model('lang')->get_info($row->lang_id);
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

	private function getUrl($url, $id = 0){
		$where['where']['lang_id'] = $this->input->post('lang_id');
		if($id)
			$where['where']['id !='] = $id;
		$where['where']['url'] = $url;
		if($this->_model()->total($where)){
			return $this->getUrl($url.strtolower(random_string('alpha',3)), $id);
		}
		return $url;
	}


}