<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Range extends MY_Controller
{
	/**
	 * Ham khoi dong
	 */
	public function __construct()
	{
		parent::__construct();
		
		// Tai cac file thanh phan
		$this->lang->load('admin/'.$this->_get_mod());
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
		$rules['name'] 			= array('name', 'required|trim|xss_clean');
		
		$this->form_validation->set_rules_params($params, $rules);
	}
	
	/**
	 * Lay input
	 */
	protected function _get_input($param = '')
	{
		$data = array();
		foreach (array('name', 'status', 'from', 'to') as $p)
		{
			$data[$p] = $this->input->post($p);
		}
		
		$data['status'] = config( ($data['status']) ? 'status_on' : 'status_off' , 'main');
		
		return ($param) ? $data[$param] : $data;
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
		$form['validation']['params'] = array('name');
		$form['submit'] = function($params) use ($type)
		{
			$data = $this->_get_input();
			$data['type'] 			= $type;
			$data['sort_order'] = $this->_model()->get_total() + 1;
			$this->_model()->create($data);

			set_message(lang('notice_add_success'));
			return admin_url('range').'?type='.$type;
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
		$this->data['info'] = $info;

		$form = array();
		//$form['view'] = 'form';
		$form['validation']['params'] = array('name');
		$form['submit'] = function($params) use ($info)
		{
			$data = $this->_get_input();

			$this->_model()->update($info->id, $data);

			set_message(lang('notice_update_success'));

			return admin_url('range').'?type='.$info->type;
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
		
		set_message(lang('notice_del_success'));
	}


	function _action_add($action)
	{
		// Lay input
		$type = $this->uri->rsegment(3);
		// Kiem tra id
		if ( $this->_mod()->check_range_type($type)) return;

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
		$types = $this->_mod()->get_range_types();
		if( !isset( $filter['type']) && count($types)>0){
			$type =$types[1];
			$filter['type']=$filter_input['type']=$type;
		}
		else
			$type = $filter['type'];

		$this->data['filter'] = $filter_input;

		$input['where']['type']   = $type;
		$list = $this->_model()->get_list($input);
		$actions = array('edit', 'del', 'translate');
		$list = admin_url_create_option($list, strtolower(__CLASS__), 'id', $actions);
		foreach ($list as $row)
		{
			// Lay danh sach item
			$row = $this->_mod()->add_info($row);
			$row = $this->_mod()->url($row);
			$row->_url_translate = admin_url("translate/table/range/".$row->id);
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