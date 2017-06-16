<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Question_answer extends MY_Controller {
	
	/**
	 * Ham khoi dong
	 */
	function __construct()
	{
		parent::__construct();
		
		// Tai cac file thanh phan
		$this->load->model('question_answer_model');
		$this->lang->load('site/question_answer');
	}

	/**
	 * Gan dieu kien cho cac bien
	 */
	function _set_rules($params = array())
	{
		if (!is_array($params))
		{
			$params = array($params);
		}
		
		$rules 						= array();
		$rules['question'] 			= array('question', 'required|trim|xss_clean|min_length[6]|max_length[255]|filter_html|callback__check_question');

		foreach ($params as $param)
		{
			if (isset($rules[$param]))
			{
				$this->form_validation->set_rules($param, 'lang:'.$rules[$param][0], $rules[$param][1]);
			}
		}
	}
	
	/**
	 * Kiem tra id question_answer cha
	 */
	function _check_question($value)
	{
		
		if (!user_is_login())
		{
			$this->form_validation->set_message(__FUNCTION__, 'Bạn cần đăng nhập để sử dụng chức năng này');
			return FALSE;
		}
	
		return TRUE;
	}
	


	/**
	 * Gui lien he
	 */
	function index()
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
			$params = array('question');
			
			$this->_set_rules($params);
			
			// Xu ly du lieu
			$result = array();
			if ($this->form_validation->run())
			{
				$user = user_get_account_info();

				// Lay question
				$question = $this->input->post('question');
				$question = strip_tags($question);

				// Them du lieu vao data
				$data = array();
				$data['question']	    = $question;
				$data['user_id']        = $user->id;
				$data['created']	    = now();

				$comment_active_status = mod("product")->setting('comment_auto_verify');
				if ($comment_active_status == config('status_on', 'main'))
					$data['status'] = config('verify_yes', 'main');

				model("question_answer")->create($data);
				// Khai bao du lieu tra ve
				$result['complete']   = TRUE;
				if($comment_active_status == config('status_off', 'main')){
					$result['msg_toast']        = lang('notice_send_question_answer_success_need_verifed');;
					//$result['location']   = site_url();
					//$result['reload'] = TRUE;
					//$this->session->set_flashdata('flash_message', array('success', 'Đã gửi câu hỏi thành công,chúng tôi sẽ kiểm duyệt và trả lời bạn sớm nhất'));
				}else{
					$result['reload'] = TRUE;
					$result['msg_toast']        = lang('notice_send_question_answer_success');;
				}
			}
			else
			{
				foreach ($params as $param)
				{
					$result[$param] = form_error($param);
				}
			}
			
			
			//Form Submit
			$this->_form_submit_output($result);
		}

		$this->_create_list();
		$this->_display();
	}
	/**
	 * Tao danh sach hien thi
	 */
	protected function _create_list($filter = array(), $input = array(), $base_url = '')
	{
		// Lay tong so
		$total = $this->_model()->filter_get_total($filter);

		$page_size = 20;//module_get_setting('news', 'list_limit');
		$limit=0;
		if($total>0){
			$limit = $this->input->get('per_page');
			$limit = max(0, min($limit, get_limit_page_last($total, $page_size)));
		}
		// Lay danh sach
		$input['limit'] = array($limit, $page_size);
		$filter['status'] = config('verify_yes', 'main');
		$_list = $this->_model()->filter_get_list($filter, $input);
		//pr_db($list);
		// Xu ly list
		$list=[];
		foreach ($_list as $row) {
			if (!$row->user_id)
				continue;
			//$row = $this->_mod()->add_info($row);
			$user = mod("user")->get_info($row->user_id);
			if (!$user)
				continue;
			$image_name = (isset($user->avatar)) ? $user->avatar : '';
			$row->user_avatar = file_get_image_from_name($image_name, public_url('img/user_no_image.png'));
			//$row->user = $user;
			//$row->_created = get_date($row->created);
			$row->_created_time = get_date($row->created, 'time');
			$list[]=$row;
		}
		$this->data['list'] = $list;

		// Tao chia trang
		$pages_config = array();
		$pages_config['page_query_string'] = TRUE;
		$pages_config['base_url'] 	= ( ! $base_url) ? current_url().'?' : $base_url;
		$pages_config['total_rows'] = $total;
		$pages_config['per_page'] 	= $page_size;
		$pages_config['cur_page'] 	= $limit;
		$this->data['pages_config'] = $pages_config;
	}

}