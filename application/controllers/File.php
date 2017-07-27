<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class File extends MY_Controller {
	
	/**
	 * Ham khoi dong
	 */
	function __construct()
	{
		parent::__construct();
		
		// Tai cac file thanh phan
		$this->load->helper('file');
		$this->load->model('file_model');
		$this->lang->load('admin/file');
	}
	/**
	 * Remap method
	 */

	function _remap($method)
	{

		if (in_array($method, array('del','download' )))
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
	 * Thuc hien upload file
	 */
	function upload()
	{
		if(!user_is_login()){
			$output = json_encode(array());
			set_output('json', $output);
		}
		// Kiem tra ma bao mat
		$params = array('mod', 'file_type', 'allowed_types', 'status', 'server', 'table', 'table_id', 'table_field', 
			'resize', 'resize_width', 'resize_height', 'thumb', 'thumb_width', 'thumb_height', 'field',
		);
		/*if ( ! security_check_query($params, 'upload'))
		{
			$output = json_encode(array());
			set_output('json', $output);
		}*/
		
		// Kiem tra file
		if ( ! isset($_FILES['file']['name']))
		{
			$output = json_encode(array());
			set_output('json', $output);
		}
		
		// Xoa cac file tam thoi
		file_del_temporary();
		
		
		// Lay cac bien dau vao
		$mod 			= $this->input->get('mod');
		$file_type 		= $this->input->get('file_type');
		$allowed_types 	= $this->input->get('allowed_types');
		$status 		= $this->input->get('status');
		$server 		= $this->input->get('server');
		$table 			= $this->input->get('table');
		$table_id 		= $this->input->get('table_id');
		$table_field 	= $this->input->get('table_field');
		$field 			= $this->input->get('field');
		
		// Xu ly bien
		$status = ($status != config('file_public', 'main')) ? config('file_private', 'main') : $status;
		$field 	= ( ! $field) ? 'file' : $field;
		
		$allowed_types = explode('|', $allowed_types);
		foreach ($allowed_types as $i => $v)
		{
			$v = trim($v);
			if (
				! $v ||
				preg_match('#[^a-z0-9]#i', $v) ||
				preg_match('#(php|phtml)#i', $v)
			)
			{
				unset($allowed_types[$i]);
			}
		}
		
		
		// Lay folder upload file
		$folders 	= config('file', 'main');
		$folder 	= $folders[$status];
		
		// Tao config upload
		$config 				= config('upload', 'main');
		$path_base= $config['path'].$config['folder'].'/'.$folder;
		$path= $path_base;//.'/'.$table;
		/*if(!is_dir($path)) {
			$this->load->helper('directory');
			directory_create($path_base,$table);
		}*/
		$config['upload_path'] 	= $path;
		$config['max_size'] 	= $config['max_size_admin'];
		$config['file_name'] 	= file_create_new_name($_FILES[$field]['name']);
		$config['allowed_types']= ($file_type == 'image') ? $config['img']['allowed_types'] : $config['allowed_types'];
		if (count($allowed_types))
		{
			$config['allowed_types'] = implode('|', $allowed_types);
		}
		
		// Thuc hien upload file
		$this->load->library('upload', $config);
		if ($this->upload->do_upload($field))
		{
			// Neu che do la single file thi xoa cac file cu
			if ($mod == 'single')
			{
				$input = array();
				$input['where']['table'] 		= $table;
				$input['where']['table_id'] 	= $table_id;
				$input['where']['table_field'] 	= $table_field;
				$list_file = $this->file_model->get_list($input);
				foreach ($list_file as $file)
				{
					file_del($file);
				}
			}
			// Lay thong tin thanh vien
			$user = $this->_get_user_info();

			// Lay thong tin cua file vua upload
			$upload_data = $this->upload->data();
			// Them vao table file
			$data = array();
			$data['file_name'] 		= $upload_data['file_name'];
			$data['orig_name'] 		= $upload_data['orig_name'];
			$data['status'] 		= $status;
			$data['server'] 		= config( ($server) ? 'verify_yes' : 'verify_no' , 'main');
			$data['table'] 			= $table;
			$data['table_id'] 		= $table_id;
			$data['table_field'] 	= $table_field;
			$data['user_id'] 	= $user->id;
			$data['created'] 		= now();
			$this->file_model->create($data);
			
			// Tao thumb cho hinh anh
			if ($this->input->get('thumb'))
			{
				$thumb_size = array();
				$thumb_size['width'] 	= $this->input->get('thumb_width');
				$thumb_size['height'] 	= $this->input->get('thumb_height');
				$thumb_size = ( ! $thumb_size['width']) ? array() : $thumb_size;
				file_create_thumb($upload_data['full_path'], $thumb_size);
				//pr($upload_data);
				/*$file_thumb=trim($upload_data['file_path'],'//');
				$file_thumb .='_thumb/'.$upload_data['file_name'];
				file_create_thumb($file_thumb, $thumb_size);*/
			}
			
			// Resize hinh anh
			if ($this->input->get('resize'))
			{
				$resize_size = array();
				$resize_size['width'] 	= $this->input->get('resize_width');
				$resize_size['height'] 	= $this->input->get('resize_height');
				$resize_size = ( ! $resize_size['width']) ? array() : $resize_size;
				file_resize($upload_data['full_path'], $resize_size, TRUE);

			}
			
			// Chuyen file len server luu tru
			if ($server)
			{
				$name_fix = array();
				if ($this->input->get('thumb'))
				{
					array_push($name_fix, 'thumb');
				}
				
				file_upload_server($upload_data['file_name'], $status, $name_fix);
			}
			
			// Khai bao du lieu tra ve
			$output = json_encode(array('complete' => TRUE));
			set_output('json', $output);
		}
		
		$output = json_encode(array());
		set_output('json', $output);
	}
	
	/**
	 * Lay danh sach file
	 */
	function index()
	{
		// Kiem tra ma bao mat
		$params = array('table', 'table_id','table_field', 'type');
		/*if (!security_check_query($params))
		{
			$output = json_encode(array());
			set_output('json', $output);
		}*/

		
		// Lay gia tri dau vao
		$table 			= $this->input->get('table');
		$table_id 		= $this->input->get('table_id');
		$table_field 	= $this->input->get('table_field');
		$file_type 		= $this->input->get('file_type');
		
		// Lay danh sach file
		$list = $this->file_model->get_list_of_mod($table, $table_id, $table_field);
		foreach ($list as $row)
		{
			$row = file_add_info($row);
			$row->_url_del 		= site_url('file/del').'?'.security_create_query(array('id' => $row->id));
			$row->_url_download = site_url('file/download').'?'.security_create_query(array('id' => $row->id));
			if (isset($row->table) && isset($row->table_id))
			{
				$row->_url_get 	= site_url('file/get').'?'.security_create_query(array('table' => $row->table, 'table_id' => $row->table_id, 'table_field' => $row->table_field,));
			}
		}
		//pr($list);
		$this->data['list'] = $list;
		
		// Luu cac bien gui den view
		$this->data['message'] = get_message();
		$this->data['url_update_order'] = current_url().'?act=update_order';
		$this->data['sort'] = (int) $this->input->get('sort');
		
		// Hien thi view
		$temp = ($file_type == 'image') ? 'index_image' : 'index';
		$this->load->view('admin/file/'.$temp, $this->data);
	}
	
	/**
	 * Lay thong tin cua 1 file
	 */
	function get()
	{
		// Kiem tra ma bao mat
		$params = array('table', 'table_id','table_field');
		/*if (!security_check_query($params))
		{
			$output = json_encode(array());
			set_output('json', $output);
		}*/

		// Lay gia tri dau vao
		$table 			= $this->input->get('table');
		$table_id 		= $this->input->get('table_id');
		$table_field 	= $this->input->get('table_field');
		
		// Lay thong tin cua file
		$where = array();
		$where['table'] 		= $table;
		$where['table_id'] 		= $table_id;
		$where['table_field'] 	= $table_field;
		$info = $this->file_model->get_info_rule($where);
		$info = file_add_info($info);
		
		$info = (empty($info)) ? new stdClass() : $info;
		$info->id 	= (isset($info->id)) ? $info->id : 0;
		$info->_url = (isset($info->_url)) ? $info->_url : public_url('img/no_image.png');
		$info->_url_del 		= site_url('file/del').'?'.security_create_query(array('id' => $info->id));
		$info->_url_download = site_url('file/download').'?'.security_create_query(array('id' => $info->id));
		if (isset($info->table) && isset($info->table_id))
		{
			$info->_url_get 	= site_url('file/get').'?'.security_create_query(array('table' => $info->table, 'table_id' => $info->table_id, 'table_field' => $info->table_field));
		}
		// Lay dung luong cua file
		if (isset($info->_path))
		{
			$file_info = get_file_info($info->_path);
			$info->_size = (isset($file_info['size'])) ? byte_format($file_info['size']) : '';
		}
		
		// Loai bo cac bien khong can thiet
		foreach (array('_path', '_path_thumb', 'created', 'status', 'table', 'table_id', 'table_field', 'user_id') as $p)
		{
			if (isset($info->$p))
			{
				unset($info->$p);
			}
		}
		
		// Tra lai ket qua
		$output = json_encode($info);
		set_output('json', $output);
	}

	/**
	 * Thuc hien hanh dong voi file
	 */
	function _action($action)
	{
		// Kiem tra ma bao mat
		$params = array('id');
		if (!security_check_query($params))
		{
			set_message(lang('notice_page_not_found'));
			return FALSE;
		}


		// Lay thong tin file
		$id = $this->input->get('id');
		$file = $this->file_model->get_info($id);
		if (!$file)
		{
			set_message(lang('notice_page_not_found'));
			return FALSE;
		}

		// Neu day khong phai file do thanh vien hien tai upload
		$user = $this->_get_user_info();
		if ($user->id != $file->user_id)
		{
			set_message(lang('notice_page_not_found'));
			return FALSE;
		}


		// Chuyen den ham tuong ung
		$this->{'_'.$action}($file);
	}

	/**
	 * Xoa file
	 */
	function _del($file)
	{
		if ( ! file_del($file))
		{
			set_message(lang('notice_page_not_found'));
		}
		
		set_message(lang('notice_del_success'));
	}
	
	/**
	 * Download file
	 */
	function _download($file)
	{
		if ( ! file_download($file->id))
		{	
			set_message(lang('notice_page_not_found'));
			redirect_admin();
		}
	}

	/*
     * ------------------------------------------------------
     *  Other function
     * ------------------------------------------------------
     */
	/**
	 * Lay thong tin user
	 */
	private function _get_user_info()
	{
		$user = user_get_account_info();
		if (!$user)
		{
			$user = new stdClass();
			$user->id = 0;
		}

		return $user;
	}


}
