<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Album_cat extends MY_Controller
{
	/**
	 * Ham khoi dong
	 */
	public function __construct()
	{
		parent::__construct();
		
		// Tai cac file thanh phan
		//$this->lang->load('admin/'.$this->_get_mod());
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
	/**
	 * Gan dieu kien cho cac bien
	 */
	function _set_rules($params)
	{
		if (!is_array($params))
		{
			$params = array($params);
		}

		$rules = array();
		$rules['status'] 			= array('status', 'trim|is_natural');
		$rules['feature'] 			= array('feature', 'trim|is_natural');
		//$rules['image'] 		= array('image', 'callback__check_image');

		$this->form_validation->set_rules_params($params, $rules);

	}



	/**
	 * Kiem tra image
	 */
	public function _check_image()
	{
		if ( ! $this->_get_image())
		{
			$this->form_validation->set_message(__FUNCTION__, lang('required'));
			return FALSE;
		}

		return TRUE;
	}


	/**
	 * Cap nhat image
	 */
	protected function _update_image($id)
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
	 * Lay image
	 */
	protected function _get_image($id = NULL)
	{
		if (is_null($id))
		{
			$id = $this->_get_id_cur();
		}

		$image = model('file')->get_info_of_mod($this->_get_mod(), $id, 'image', 'id, file_name');

		return $image;
	}
	protected function _get_id_cur()
	{
		return ($this->uri->rsegment(2) == 'add')
			? fake_id_get($this->_get_mod())
			: $this->uri->rsegment(3);
	}
	/*
       * ------------------------------------------------------
       *  Prepare data handle
       * ------------------------------------------------------
       */
	protected function _fields()
	{
		return  array(
				'name',
			 'intro',  'sort_order',
			 'status', 'feature',
		);
	}

	protected function _get_params()
	{
		$params = $this->_fields();
		$params[] = 'image';

		return $params;
	}

	/**
	 * Lay input
	 */

	protected function _get_inputs($param = '')
	{
		$data = array();
		$fields = $this->_fields();
		foreach ($fields as $f) {
			$v = $this->input->post($f);
			if (!$v) $v = '';

			/*if (in_array($f, array(	'cat_j_type_id'	))) {
				$v = json_encode($v);
			}*/
			//$v =$this->_get_user_id($v);
			//	$v= currency_handle_input($v);
			$data[$f] = $v;
		}
		$data['status'] = config( ($data['status']) ? 'status_on' : 'status_off' , 'main');

		$image = $this->_get_image();
        if ($image)
        {
            $data['image_id']	= $image->id;
            $data['image_name']	= $image->file_name;
        }
		//pr($data);
		return ($param) ? $data[$param] : $data;
	}


	/**
	 * Tao data gui den view
	 *
	 * @param int $id
	 */
	protected function _create_view_data($id)
	{
		// Khai bao cac bien cua widget upload
		$widget_upload = array();
		$widget_upload['mod'] 			= 'single';
		$widget_upload['file_type'] 	= 'image';
		$widget_upload['status'] 		= config('file_public', 'main');
		$widget_upload['resize'] 		= TRUE;
		$widget_upload['thumb'] 		= TRUE;
		$widget_upload['url_update']	= ($id > 0) ? current_url().'?act=update_image' : null;

		$widget_upload['table'] 		= $this->_get_mod();
		$widget_upload['table_id'] 		= $id;
		// up anh cua cat
		$widget_upload['table_field'] 	= 'image';
		$this->data['widget_upload'] 	= $widget_upload;
		// up anh cua tac gia
		//$widget_upload['table_field'] 	= 'author';
		//$this->data['upload_author'] 	= $widget_upload;

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
	public function add()
	{
		$fake_id = $this->_get_id_cur();
		$form = array();
		$form['view'] = 'form';
		$form['validation']['params'] = $this->_get_params();
		$form['submit'] = function()use ($fake_id)
		{
			$data = $this->_get_inputs();
			$data['sort_order'] = $this->_model()->get_total() + 1;
			$id = 0;
			$this->_model()->create($data,$id);
			// Cap nhat lai table_id table file
			model('file')->update_table_id_of_mod($this->_get_mod(), $fake_id, $id);
			fake_id_del($this->_get_mod());

			set_message(lang('notice_add_success'));
			
			return admin_url($this->_get_mod());
		};
		$form['form'] = function() use ($fake_id)
		{
			$this->_create_view_data($fake_id);

			$this->_display('form');
		};
		$this->_form($form);
	}
	
	/**
	 * Chinh sua
	 */
	protected function _edit($info)
	{
		$info  =$this->_mod()->add_info($info);
		$this->data['info'] = $info;
		// Cap nhat thong tin
		if ($this->input->get('act') == 'update_image')
		{
			$this->_update_image($info->id);
			return;
		}
		$form = array();
		$form['view'] = 'form';
		$form['validation']['params'] =$this->_get_params();
		$form['submit'] = function() use ($info)
		{
			$data = $this->_get_inputs();
			$this->_model()->update($info->id, $data);
			set_message(lang('notice_update_success'));
			
			return admin_url($this->_get_mod());
		};
		$form['form'] = function() use ($info)
		{
			$this->_create_view_data($info->id);

			$this->_display('form');
		};
		$this->_form($form);
	}
	
	/**
	 * Xoa
	 */
	protected function _del($info)
	{
		$this->_model()->del($info->id);

		$this->load->helper('file');
		file_del_table($this->_get_mod(), $info->id);
		set_message(lang('notice_del_success'));
	}
	
	/**
	 * Danh sach
	 */
	public function index()
	{
		$list = array();
		$list['filter'] = TRUE;
		$list['filter_fields'] = array('id', 'name','status');
		$list['page'] = FALSE;
		$list['sort'] = TRUE;
		$list['display'] = false;
		$this->_list($list);
		
		foreach ($this->data['list'] as $row)
		{
			$row = $this->_mod()->add_info($row);
			$row = $this->_mod()->url($row);
		}
		
		$this->_display();
	}

}