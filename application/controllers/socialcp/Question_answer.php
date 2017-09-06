<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Question_answer extends MY_Controller
{

	/**
	 * Ham khoi dong
	 */
	function __construct()
	{
		parent::__construct();

		// Tai cac file thanh phan
		$this->lang->load('admin/' . $this->_get_mod());

	}


	/**
	 * Remap method
	 */
	public function _remap($method, $params = array())
	{
		return $this->_remap_action($method, $params, array('view', 'verify', 'unverify', 'del'));
	}

	/*
  * ------------------------------------------------------
  *  Action handle
  * ------------------------------------------------------
  */
	/**
	 * Gan dieu kien cho cac bien
	 */
	function _set_rules($params = array())
	{
		if (!is_array($params)) {
			$params = array($params);
		}

		$rules = array();
		$rules['answer'] = array('answer', 'required|trim|xss_clean');
		$this->form_validation->set_rules_params($params, $rules);
	}
	/*
     * ------------------------------------------------------
     *  List handle
     * ------------------------------------------------------
     */
	function index()
	{
		// Xu ly form
		if ($this->input->post('_submit')) {
			$this->load->library('form_validation');
			$this->load->helper('form');
			$id= $this->input->post('id');
			// Gan dieu kien cho cac bien
			$params = array('answer');
			$this->_set_rules($params);
			// Xu ly du lieu
			$result = array();
			if ($this->form_validation->run()) {
				// Them du lieu vao data
				$data = array();
				$data ['answer'] = $this->input->post('answer');
				$this->_model()->update($id,$data);
				set_message(lang('notice_request_success'));
				// Khai bao du lieu tra ve
				$result['complete'] = TRUE;
				$result['reload'] = 1;
			} else {
				foreach ($params as $param) {
					$result[$param] = form_error($param);
				}

			}

			$output = json_encode($result);
			set_output('json', $output);
		}
		// Lay gia tri cua filter dau vao
		$filter_input = array();
		$filter_fields =  array('id', 'user',  'status', 'readed', 'created');
		$filter = $this->_mod()->create_filter($filter_fields, $filter_input);
		$this->data['filter'] = $filter_input;
		$total = $this->_model()->filter_get_total($filter);
		$page_size = config('list_limit', 'main');
		$limit = $this->input->get('per_page');
		$limit = min($limit, get_limit_page_last($total, $page_size));
		$limit = max(0, $limit);

		// Lay danh sach
		$input = array();
		$input['limit'] = array($limit, $page_size);
		$list = $this->_model()->filter_get_list($filter, $input);
		$actions = array('view', 'del');
		//$list = admin_url_create_option($list, 'comment', 'id', $actions);
		$list = $this->_url_create_option($list,'id',$actions);
		foreach ($list as $it) {
			$it =$this->_mod()->add_info($it);
			$it->user = mod('user')->get_info($it->user_id);
			foreach ($actions as $action) {
				$it->{'_can_' . $action} = $this->_mod()->can_do($it, $action);
			}
		}
		$this->data['list'] = $list;

		// Tao chia trang
		$pages_config = array();
		$pages_config['page_query_string'] = TRUE;
		$pages_config['base_url'] = $this->_url() . '?' . url_build_query($filter_input);
		$pages_config['total_rows'] = $total;
		$pages_config['per_page'] = $page_size;
		$pages_config['cur_page'] = $limit;
		$this->data['pages_config'] = $pages_config;

		//$this->data['actions'] = array('del', 'verify', 'unverify',);
		// Tao action list
		$actions = array();
		foreach (array('del', 'verify', 'unverify',) as $v) {
			$url = admin_url(strtolower(__CLASS__) . '/' . $v);
			if (!admin_permission_url($url)) continue;

			$actions[$v] = $url;
		}
		$this->data['actions'] = $actions;

		// Hien thi view
		$this->_display();
	}

	/*
     * ------------------------------------------------------
     *  Action handle
     * ------------------------------------------------------
     */

	function _action($action)
	{
		// Lay input
		$ids = $this->uri->rsegment(3);
		$ids = (!$ids) ? $this->input->post('id') : $ids;
		$ids = (!is_array($ids)) ? array($ids) : $ids;

		// Thuc hien action
		foreach ($ids as $id) {
			// Xu ly id
			$id = (!is_numeric($id)) ? 0 : $id;

			// Kiem tra id
			$info = $this->_model()->get_info($id);
			if (!$info) continue;


			// Kiem tra co the thuc hien hanh dong nay khong
			if (!$this->_mod()->can_do($info, $action)) continue;

			if (in_array($action, array('verify', 'unverify',))) {
				// thuc hien yeu cau
				set_message(lang('notice_update_success'));
				$this->_mod()->action($info, $action);
				$output = array('complete' => TRUE);
				set_output('json', json_encode($output));
			} else {
				$this->{'_' . $action}($info);
			}

		}
	}

	/**
	 * Kiem tra co the thuc hien hanh dong hay khong
	 */
	function _can_do($info, $action)
	{
		switch ($action) {
			case 'view':
			case 'del': {
				return TRUE;
			}
		}

		return FALSE;
	}

	/**
	 * Xem chi tiet
	 */
	function _view($info)
	{
		// Gan trang thai
		if ($info->readed == config('verify_no', 'main')) {
			$this->_model()->update_field($info->id, 'readed', config('verify_yes', 'main'));
		}

		// Xu ly thong tin
		$info->_created = get_date($info->created);
		$info->_created_full = get_date($info->created, 'full');
		$info->user = mod('user')->get_info($info->user_id);
		// Luu bien gui den view
		$this->data['info'] = $info;
		// Hien thi view
		$this->_display('view', NULL);
	}

	/**
	 * Xoa du lieu
	 */
	function _del($info)
	{
		// Thuc hien xoa
		$this->_model()->del($info->id);

		// Gui thong bao
		set_message(lang('notice_del_success'));
	}

}