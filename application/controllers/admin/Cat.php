<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cat extends MY_Controller
{
	/**
	 * Ham khoi dong
	 */
	public function __construct()
	{
		parent::__construct();
		
		// Tai cac file thanh phan
		$this->lang->load('admin/'.$this->_get_mod());

		$this->data['langs'] = lang_get_list();
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
		elseif (in_array($method, array('add')))
		{
			$this->_action_add($method);
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
		if (!is_array($params))
		{
			$params = array($params);
		}

		$rules = array();
		$rules['status'] 			= array('status', 'trim|is_natural');
		$rules['feature'] 			= array('feature', 'trim|is_natural');
		//$rules['image'] 		= array('image', 'callback__check_image');

		$lang_default = setting_get('config-site_language');
		foreach ($this->data['langs'] as $lang ) {
			if($lang->id != $lang_default) continue;
			foreach ($this->_content_fields() as $p) {
				if(!in_array($p,array('name'/*,'intro'*/))) continue;
				$r= $p . '[' . $lang->id . ']';
				$rules [$r] = array ($p,'required|trim|xss_clean');
			}
		}
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
	/**
	 * Lay id xu ly hien tai
	 *
	 * @return int
	 */
	protected function _get_id_cur()
	{
		return ($this->uri->rsegment(2) == 'add')
			? fake_id_get($this->_get_mod())
			: $this->uri->rsegment(3);
	}

	protected function _fields()
	{
		return $params = array('name', 'intro', 'icon','parent_id','is_anime', 'feature', 'status'	);
	}
	protected function _content_fields()
	{
		return $this->_model()->content_fields;
	}

	protected function _get_params()
	{
		$params = $this->_fields();

		/*foreach ($this->data['langs'] as $lang ) {
			foreach ($this->_content_fields() as $p) {

				$params[] = $p . '[' . $lang->id . ']';
			}
		}*/
		return $params;
	}

	/**
	 * Lay input
	 */
	protected function _get_input($type,$param = '')
	{
		$data = array();
		$fields =$this->_fields();
		foreach ($fields as $f){
			$v =$this->input->post($f);
			if(!is_null($v)){
				$data[$f] = $v;
			}
		}
		if(in_array($type,mod('cat')->config('cat_hiarachy_types'))){
			if(isset($data['parent_id']))
			{
					if($data['parent_id'] == 0){
						$data['level']=0 ;
					}
					else{
						$parent=$this->_model()->get_info($data['parent_id']);
						$data['level']=$parent->level + 1;
					}
			}
		}
		$data['status'] = config( ($data['status']) ? 'status_on' : 'status_off' , 'main');
		$data['feature'] = config( ($data['status']) ? 'status_on' : 'status_off' , 'main');
		//pr($data);
		$image = $this->_get_image();
		if ($image)
		{
			$data['image_id']	= $image->id;
			$data['image_name']	= $image->file_name;
		}
		return ($param) ? $data[$param] : $data;
	}
	protected function _get_content_inputs()
	{
		$data = array();
		$fields =$this->_content_fields();
		foreach ($fields as $f)
		{
			$vs = $this->input->post($f);
			//echo $f; pr($vs,0);
			if(is_array($vs))
				foreach ($vs as $l => $v)
				{
					if(!$v) continue;
					$data[$l][$f] =$v;
				}

		}
		//pr($data);
		return  $data;
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

		$widget_upload['table'] 		= $this->_get_mod();
		$widget_upload['table_id'] 		= $id;

		$widget_upload['url_update']	= ($id > 0) ? current_url().'?act=update_image' : null;
		$widget_upload['table_field'] 	= 'image';
		$this->data['widget_upload'] 	= $widget_upload;
		// Other
		$this->data['action'] = current_url();


	}

	protected function _update_infos($id)
	{
		// Cap nhat vao content
		//$content_data = $this->_get_content_inputs();
		//model('cat_content')->set($id,$content_data);

		// cap nhap lai cache
		$info =$this->_model()->get_info($id);
		$this->_model()->cache_update($info->type);
		$this->_model()->cache_update('_all');

	}
/*
 * ------------------------------------------------------
 *  Action handle
 * ------------------------------------------------------
 */
	/**
	 * Them moi
	 */
	public function _add($type)
	{
		$fake_id = $this->_get_id_cur();
		$form = array();
		//$form['view'] = 'form';
		$form['validation']['params'] = $this->_get_params();
		$form['submit'] = function() use ($type,$fake_id)
		{
			$data = $this->_get_input($type);
			$data['type'] 			= $type;
			$data['sort_order'] = $this->_model()->get_total() + 1;
			$id = 0;
			$this->_model()->create($data,$id);
			$this->_update_infos($id);
			// Cap nhat lai table_id table file
			model('file')->update_table_id_of_mod($this->_get_mod(), $fake_id, $id);
			fake_id_del($this->_get_mod());


			set_message(lang('notice_add_success'));
			return admin_url('cat').'?type='.$type;
		};
		$form['form'] = function()  use ($type,$fake_id)
		{
			$parents	 	= $this->_model()->get_list_hierarchy(array('where'=>array('type'=>$type)));
			foreach ($parents as $row)
			{
				$row = $this->_mod()->add_info($row);
				//$row->name= $row->_content->name;
			}
			$this->data['parents']=$parents;
			$this->data['type'] 	= $type;
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
		$info->_contents =model('cat_content')->get($info->id);
		$this->data['info'] = $info;
		// Cap nhat thong tin
		if ($this->input->get('act') == 'update_image')
		{
			$this->_update_image($info->id);
			return;
		}
		$form = array();
		//$form['view'] = 'form';
		$form['validation']['params'] = $this->_get_params();
		$form['submit'] = function($params) use ($info)
		{
			$data = $this->_get_input($info->type);

			$this->_model()->update($info->id, $data);
			$this->_update_infos($info->id);

			set_message(lang('notice_update_success'));

			return admin_url('cat').'?type='.$info->type;
		};

		$form['form'] = function() use ($info)
		{
			//$this->data['parents']	 	= $this->_model()->get_hierarchy_data($info->id);
			$parents	= $this->_model()->get_list_hierarchy(array('where'=>array('type'=>$info->type,'id <>'=>$info->id)));
			foreach ($parents as $row)
			{
				$row = $this->_mod()->add_info($row);
				//$row->name= $row->_content->name;
			}
			$this->data['parents']=$parents;
			$this->data['type'] 	= $info->type;
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

		// chu y du cat co su dung song ngu, do do phan add, edit dc goi truoc cat_content model update du lieu
		// do do ta phai goi update cache o trong Controller khi goi ham add,edit
		//$where 	= $params[0];
		//$id = $where[$this->key];


		$this->_model()->del($info->id);

		// Cap nhat lai cache
		//echo '<br>Cap nhap loai:';
		$this->_model()->cache_update($info->type);
		//echo '<br>Cap nhap All:';
		$this->_model()->cache_update('_all');

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
			$this->{'_'.$action}($info);
		}
	}
	function _action_add($action)
	{
		// Lay input
		$type = $this->uri->rsegment(3);
		// Kiem tra id
		if ( $this->_mod()->check_cat_type($type)) return;

		// Kiem tra co the thuc hien hanh dong nay khong
		//if ( ! $this->_mod()->can_do($info, $action)) return;

		// Chuyen den ham duoc yeu cau
		$this->{'_'.$action}($type);
	}
	
	/**
	 * Danh sach
	 */
	public function index()
	{
		// Tai cac file thanh phan
		$this->load->helper('form');
		$this->load->library('form_validation');
		// Cap nhat sort_order
		if ($this->input->get('act') == 'update_order')
		{
			$items = $this->input->post('items');
			$items = explode(',', $items);

			foreach ($items as $i => $id)
			{
				$data = array();
				$data['sort_order']	= $i;
				$this->_model()->update($id, $data);
				$info =$this->_model()->get_info($id);
				$this->_model()->cache_update($info->type);
			}
			$this->_model()->cache_update('_all');
			$output = json_encode(array('complete' => TRUE));
			set_output('json', $output);
		}

		$filter_input 	= array();
		$filter_fields 	= array('type');
		$filter = $this->_mod()->create_filter($filter_fields, $filter_input);

		// Lay danh sach loai
		$types = $this->_mod()->get_cat_types();
		if( !isset( $filter['type']) && count($types)>0){
			$type =$types[1];
			$filter['type']=$filter_input['type']=$type;
		}
		else
			$type = $filter['type'];

		$this->data['filter'] = $filter_input;

		$filter['type']   = $type;
		$list = $this->_model()->get_list_hierarchy([],$filter);
		//pr_db();
		$actions = array('edit', 'del', 'translate');
		$list = admin_url_create_option($list, strtolower(__CLASS__), 'id', $actions);
		foreach ($list as $row)
		{
			// Lay danh sach item
			$row = $this->_mod()->add_info($row);
			$row = $this->_mod()->url($row);
			$row->_url_translate = admin_url("translate/table/cat/".$row->id);
			foreach ($actions as $action)
			{
				$row->{'_can_'.$action} = ($this->_mod()->can_do($row, $action) && admin_permission_url($row->{'_url_'.$action})) ? TRUE : FALSE;
			}
		}
		$this->data['sort_url_update'] = current_url().'?act=update_order';
		// Luu cac bien gui den view
		$this->data['list'] = $list;
		$this->data['type']	 	= $type;
		$this->data['types']	 	= $types;
		$this->_display();
	}

}