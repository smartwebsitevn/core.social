<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Form_content extends MY_Controller
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
	protected function _set_rules($params)
	{
		$rules = array();
		//$rules['title'] 			= array('title', 'required|trim|xss_clean');
		$lang_default = setting_get('config-site_language');
		foreach ($this->data['langs'] as $lang ) {
			if($lang->id != $lang_default) continue;
			foreach ($this->_content_fields() as $p) {
				if(!in_array($p,array('title','content'))) continue;
				$r= $p . '[' . $lang->id . ']';
				$rules [$r] = array ($p,'required|trim|xss_clean');
			}
		}
		$this->form_validation->set_rules_params($params, $rules);
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
		return $params = array(/*'name', 'intro',*/  'status'	);
	}
	protected function _content_fields()
	{
		return $this->_model()->content_fields;
	}

	protected function _get_params()
	{
		$params = $this->_fields();

		foreach ($this->data['langs'] as $lang ) {
			foreach ($this->_content_fields() as $p) {

				$params[] = $p . '[' . $lang->id . ']';
			}
		}
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

		$data['status'] = config( ($data['status']) ? 'status_on' : 'status_off' , 'main');
		//pr($data);
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
	protected function _update_infos($id)
	{
		// Cap nhat vao content
		$content_data = $this->_get_content_inputs();
		model('form_content_content')->set($id,$content_data);

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
		$form = array();
		//$form['view'] = 'form';
		$form['validation']['params'] =$this->_get_params();
		$form['submit'] = function($params) use ($type)
		{
			$data = $this->_get_input($type);
			$data['type'] 			= $type;
			$data['sort_order'] = $this->_model()->get_total() + 1;
			$id = 0;
			$this->_model()->create($data,$id);
			$this->_update_infos($id);
			set_message(lang('notice_add_success'));
			return $this->_url().'?type='.$type;
		};
		$form['form'] = function()  use ($type)
		{
			$this->data['type'] 	= $type;
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
		$info->_contents =model('form_content_content')->get($info->id);
		$this->data['info'] = $info;

		$form = array();
		//$form['view'] = 'form';
		$form['validation']['params'] = $this->_get_params();
		$form['submit'] = function($params) use ($info)
		{
			$data = $this->_get_input($info->type);

			$this->_model()->update($info->id, $data);
			$this->_update_infos($info->id);
			set_message(lang('notice_update_success'));

			return $this->_url().'?type='.$info->type;
		};

		$form['form'] = function() use ($info)
		{
			$this->data['type'] 	= $info->type;
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

		// cap nhap lai cache
		$info =$this->_model()->get_info($info->id);
		$this->_model()->cache_update($info->type);
		$this->_model()->cache_update('_all');

		set_message(lang('notice_del_success'));
	}


	function _action_add($action)
	{
		// Lay input
		$type = $this->uri->rsegment(3);
		// Kiem tra id
		if ( $this->_mod()->check_form_type($type)) return;

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
			}

			$output = json_encode(array('complete' => TRUE));
			set_output('json', $output);
		}

		$filter_input 	= array();
		$filter_fields 	= array('type');
		$filter = $this->_model()->filter_create_input($filter_fields, $filter_input);

		// Lay danh sach loai
		$types = $this->_mod()->get_form_types();
		if( !isset( $filter['type']) && count($types)>0){
			$type =$types[1];
			$filter['type']=$filter_input['type']=$type;
		}
		else
			$type = $filter['type'];

		$this->data['filter'] = $filter_input;

		$input['where']['type']   = $type;
		$list = $this->_model()->get_list($input);
		$actions = array('edit', 'del');
		$list = admin_url_create_option($list, strtolower(__CLASS__), 'id', $actions);
		foreach ($list as $row)
		{
			// Lay danh sach item
			$row = $this->_mod()->add_info($row);
			$row = $this->_mod()->url($row);
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