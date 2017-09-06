<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

use App\User\UserFactory as UserFactory;
use App\User\Model\UserGroupModel as UserGroupModel;
use App\User\Handler\Form\UserGroup as UserGroupFormHandler;

class emailsend extends MY_Controller
{
	/**
	 * Ham khoi dong
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('email');
		$this->lang->load('admin/'.__CLASS__);
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
	protected function _set_rules($params)
	{
		$rules = array();
		$rules['title'] = array('title', 'required|trim|xss_clean');
		$rules['content'] = array('content', 'required|trim');
		$rules['emaillist'] = array('content', 'xss_clean|callback__checkemaillist');

		$this->form_validation->set_rules_params($params, $rules);
	}

	function _checkemaillist(){
		if($this->getEmailList())
			return true;

		$this->form_validation->set_message(__FUNCTION__, lang('error_emaillist'));
		return false;
	}
	/**
	 * Lay input
	 */
	protected function _get_input($param = '')
	{
	    $data = elements(array('title','content'),
				$this->input->post(), '');
		$data['status'] = 'pending';
		$data['total'] = count($this->getEmailList());
		$data['updated'] = $data['created'] = now();
		$data['admin_id'] = admin_get_account_info()->id;
	    return ($param) ? $data[$param] : $data;
	}
	
	/**
	 * Tao data gui den view
	 *
	 * @param int $id
	 */
	protected function _create_view_data()
	{
		// lay nhom thanh vien

		$this->data['usergroup'] = model('user_group')->get_list();
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
		$form = array();

		$form['validation']['params'] = array('title','content','emaillist');
		
		$form['submit'] = function($params)
		{
			// Lay input
			$data = $this->_get_input();
			
			// Cap nhat vao data
			$id = 0;
			$this->_model()->create($data, $id);

			// cap nhap vao email to
			model('emailsend_to')->setData($id, $this->getEmailList());
			
			set_message(lang('notice_add_success'));
			
			return admin_url(__CLASS__);
		};
		
		$form['form'] = function()
		{
			$this->_create_view_data();
			
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
		model('emailsend_to')->setData($info->id);
		set_message(lang('notice_del_success'));
	}
	
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
		$filter_fields 	= array('id', 'title', 'created', 'created_to', 'status',);
		$filter = $this->_model()->filter_create($filter_fields, $filter_input);
		$this->data['filter'] = $filter_input;

		// Lay tong so
		$total = $this->_model()->filter_get_total($filter);
		$page_size = config('list_limit', 'main');
		$limit = $this->input->get('per_page');
		$limit = max(0, min($limit, get_limit_page_last($total, $page_size)));

		// Lay danh sach
		$input = array();
		$input['order'] = array('id', 'desc');
		$input['limit'] = array($limit, $page_size);
		$list = $this->_model()->filter_get_list($filter, $input);

		$actions = array( 'del');
		$list = admin_url_create_option($list, strtolower(__CLASS__), 'id', $actions);
		foreach ($list as $row)
		{
			$row = $this->_mod()->add_info($row);
			$row->admin = admin_get_info($row->admin_id);

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

	/** chuyen text sang array
	 * @param $text
	 * @return array
	 */
	private function getDataFormText($text)
	{
		$value = preg_replace('#<br\s*/?>#i', "\n", $text);
		return explode("\n", $value);
	}

	/** lay danh sach email yeu cau gui
	 * @return array
	 */
	private function getEmailList(){
		static $email = array();
		if($email)
			return $email;

		$emaillist = $this->input->post('emaillist');
		$usergroup = $this->input->post('usergroup');
		// neu khong co usergroup nao dc chon
		if($usergroup){
			// lay danh sach user
			$where = array();
			$where['where']['?user_group_id'] = $usergroup;
			// chi lay user dang hoat dong
			$where['where']['blocked'] = '0';
			$where['select'] = 'email';
			foreach(model('user')->select($where) as $row){
				$email[] = $row->email;
			}
		}
		//kiem tra xem co email nao trong danh sach nhap ko
		if($emaillist){
			$emaillist = $this->getDataFormText($emaillist);
			if($emaillist){
				foreach($emaillist as $row) {
					if(valid_email($row)) {
						$email[] = $row;
					}
				}
			}
		}
		// loai bo email trung lap
		return array_unique($email);
	}
}