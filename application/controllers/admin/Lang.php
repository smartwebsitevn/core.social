<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lang extends MY_Controller {
	
	/**
	 * Ham khoi dong
	 */
	function __construct()
	{
		parent::__construct();
		
		// Tai cac file thanh phan
		$this->load->model('lang_model');
		$this->lang->load('admin/lang');
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
 *  Rules params
 * ------------------------------------------------------
 */
	/**
	 * Gan dieu kien cho cac bien
	 */
	function _set_rules($params)
	{
		$rules = array();
		$rules['name'] 				= array('name', 'required|trim|xss_clean|max_length[64]');
		$rules['directory'] 		= array('directory', 'required|trim|xss_clean|alpha_dash|max_length[64]|callback__check_directory');
		$rules['code'] 		= array('code', 'required|trim|xss_clean|alpha_dash|min_length[2]|max_length[3]|callback__check_directory');

		$rules['charset'] 				= array('charset', 'required|trim|xss_clean|max_length[64]');

		$rules['sort_order'] 		= array('sort_order', 'is_natural|less_than[128]');
		
		$this->form_validation->set_rules_params($params, $rules);
	}
	
	/**
	 * Kiem tra thu muc cua ngon ngu da ton tai hay chua
	 */
	function _check_directory($value)
	{
		$id = $this->uri->rsegment(3);
		$id = ( ! is_numeric($id)) ? 0 : $id;
		
		$where = array();
		$where['id !='] = $id;
		$where['directory'] = $value;
		$id = $this->lang_model->get_id($where);
		
		if ($id)
		{
			$this->form_validation->set_message(__FUNCTION__, lang('notice_already_exists'));
			return FALSE;
		}
		
		return TRUE;
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
 *  Action handle
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
			$params = array('name', 'directory', 'code','charset', 'sort_order');
			$this->_set_rules($params);
			
			// Xu ly du lieu
			$result = array();
			if ($this->form_validation->run())
			{
				// Cap nhat vao data
				$data = array();	
				$data['name']		= $this->input->post('name');
				$data['directory']	= $this->input->post('directory');
				$data['code']	    = $this->input->post('code');
				$data['charset']		= $this->input->post('charset');
				$data['sort_order']	= $this->input->post('sort_order');
				$data['status']		= ($this->input->post('status')) ? config('status_on', 'main') : config('status_off', 'main');
				$lang_id=0;
				$this->lang_model->create($data,$lang_id);

				// tao phrase cho ngon ngu moi
				/*
				$files = model('lang_file')->get_list();
				foreach($files as $file){
					// lay cac phrase cua file ngon ngu
					$list = model('lang_phrase')->filter_get_list(array('lang'=>1,'file'=>$file->id));

					if($list) {
						$lang_phrases=array();
						foreach ($list as $info) {
							// luu vao csdl key ,value va ban dich cho ngon ngu
							model('lang_phrase')->set($lang_id, $file->id, $info->key, $info->value, $info->value);
							$lang_phrases[$info->key]=$info->value;
						}
						// tao cache cho file lang
						//// neu co thay doi thi luu lai vao cache
						lang_set_cache($data['directory'],$file->file,$lang_phrases);
					}
				}
				*/
				
				//them cot moi
				$this->load->dbforge();
				$fields = array(
				     $data['directory']  => array('type' => 'longtext')
				);
				$this->dbforge->add_column('lang_phrase', $fields);
				
				// Khai bao du lieu tra ve
				$result['complete'] = TRUE;
				$result['location'] = admin_url('lang');
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
		
		
		// Luu bien gui den view
		$this->data['action'] = current_url();
		$this->data['countries'] = model('country')->get_grouped();
		// Tao cay thu muc
		$breadcrumbs = array();
		$breadcrumbs[] = array('', lang('group_system'));
		$breadcrumbs[] = array(admin_url('lang'), lang('mod_lang'));
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
			$params = array('name', 'directory','code', 'charset', 'sort_order');
			$this->_set_rules($params);
			
			// Xu ly du lieu
			$result = array();
			if ($this->form_validation->run())
			{
				// Cap nhat vao data
				$data = array();
				$data['name']		= $this->input->post('name');
				$data['charset']		= $this->input->post('charset');
				$data['directory']	= $this->input->post('directory');
				$data['code']	= $this->input->post('code');
				$data['sort_order']	= $this->input->post('sort_order');
				$data['status']		= ($this->input->post('status')) ? config('status_on', 'main') : config('status_off', 'main');
				$this->lang_model->update($info->id, $data);
				
				//Cap nhat ten caot
				if($data['directory'] != $info->directory)
				{
				    $this->load->dbforge();
				    $fields = array(
				        $info->directory => array(
				            'name' => $data['directory'],
				            'type' => 'longtext',
				        ),
				    );
				    $this->dbforge->modify_column('lang_phrase', $fields); 
				}
				
				// Khai bao du lieu tra ve
				$result['complete'] = TRUE;
				$result['location'] = admin_url('lang');
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
		
		
		// Luu bien gui den view
		$this->data['action'] 	= current_url();
		$this->data['info'] 	= $info;
		$this->data['countries'] = model('country')->get_grouped();
		// Tao cac bien gui den view
		$breadcrumbs = array();
		$breadcrumbs[] = array('', lang('group_system'));
		$breadcrumbs[] = array(admin_url('lang'), lang('mod_lang'));
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
		// Xoa ngon ngu
		$this->lang_model->del($info->id);
		// Xoa ban dich
		//model("lang_phrase")->del_rule(array('lang_id'=>$info->id));
		// Xoa thu muc cache
		lang_del_cache($info->directory);
		
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
			$info = $this->lang_model->get_info($id);
			if ( ! $info) continue;
			
			// Kiem tra co the thuc hien hanh dong nay khong
			if ( ! $this->_can_do($info, $action)) continue;
			
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
			case 'edit':
			{
				return TRUE;
			}
			case 'del':
			{
				//pr($row,false);
				if( $info->protected)
					return false;

				$default = lang_get_default();
				$default_id = (isset($default->id)) ? $default->id : 0;

				return ($info->id != $default_id) ? TRUE : FALSE;
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
		// Lay config
		$statuss = config('status', 'main');
		
		// Lay ngon ngu mac dinh
		$default = lang_get_default();
		$default_id = (isset($default->id)) ? $default->id : 0;
		
		// Lay danh sach
		$list = $this->lang_model->get_list();
		
		$actions = array('edit', 'del');
		$list = admin_url_create_option($list, strtolower(__CLASS__), 'id', $actions);
		foreach ($list as $row)
		{
			$row->_status 		= $statuss[$row->status];
			$row->_is_default 	= ($row->id == $default_id) ? TRUE : FALSE;
			
			foreach ($actions as $action)
			{
				$row->{'_can_'.$action} = ($this->_can_do($row, $action) && admin_permission_url($row->{'_url_'.$action})) ? TRUE : FALSE;
			}
		}
		$this->data['list'] = $list;

		// Breadcrumbs
		$breadcrumbs = array();
		$breadcrumbs[] = array('', lang('group_system'));
		$breadcrumbs[] = array(admin_url('lang'), lang('mod_lang'));
		$breadcrumbs[] = array(current_url(), lang('list'));
		$this->data['breadcrumbs'] = $breadcrumbs;
		
		// Hien thi view
		$this->_display();
	}
	
}