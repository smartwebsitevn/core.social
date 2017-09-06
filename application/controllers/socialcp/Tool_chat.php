<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tool_chat extends MY_Controller {

	/**
	 * Remap method
	 */
	function _remap($method)
	{
		if (in_array($method, array( 'del')))
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
	function _set_rules($params = array())
	{
		$rules = array();
		$rules['content'] 			= array('content', 'required|trim|xss_clean|min_length[6]|max_length[255]');
		$this->form_validation->set_rules_params($params, $rules);
	}
	/*
     * ------------------------------------------------------
     *  Main handle
     * ------------------------------------------------------
     */
	/**
	 * Hien thi cac thong tin tren site
	 */
	function index()
	{
		// Xu ly form
		if ($this->input->post('_submit'))
		{ 	// Tai cac file thanh phan
			$this->load->library('form_validation');
			$this->load->helper('form');
			// Gan dieu kien cho cac bien
			$params = array('content');
			$this->_set_rules($params);
			// Xu ly du lieu
			$result = array();
			if ($this->form_validation->run())
			{
				$admin = admin_get_account_info();
				// Cap nhat vao data
				$data = array();
				$data['content'] = $this->input->post('content');
				$data['admin_id']	= $admin->id;
				$data['admin_name']	= $admin->name;
				$data['created']	= now();
				model('tool_chat')->create($data);

				// tra lai du lieu
				$input=array();
				$input['limit']=array(0,10);
				$list = model('tool_chat')->get_list($input);
				$this->data['list']=$list;
				$list = $this->load->view('admin/tool_chat/list',$this->data,true);
				// Khai bao du lieu tra ve
				$result['complete'] = TRUE;
				$result['element'] = array('pos'=>'#in-chat','data'=>$list);
				//$result['location'] = admin_url('admin');
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
	}

	/**
	 * Xoa du lieu
	 */
	function _del($info)
	{
		// Thuc hien xoa
		model('tool_chat')->del($info->id);

		$result['complete'] = TRUE;
		$this->_form_submit_output($result);
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
			$info = model('tool_chat')->get_info($id);
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
			case 'del':
			{
				$admin =admin_get_account_info();
				if( $admin->is_root){
					return TRUE;
				}
			}


		}

		return FALSE;
	}



}