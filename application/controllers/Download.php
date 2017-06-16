<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class download extends MY_Controller
{
	/**
	 * Ham khoi dong
	 */
	public function __construct()
	{
		parent::__construct();
		
		// Tai cac file thanh phan
		$this->lang->load('site/'.__CLASS__);
		$this->data['class'] = __CLASS__;
	}
	
	/**
	 * Danh sach
	 */
	public function index()
	{
		page_info('title', lang(__CLASS__));
		$this->data['breadcrumbs'] = array(current_url(), lang(__CLASS__), lang(__CLASS__));
		$this->_display();
	}



	/**
	 * Xem chi tiet
	 */
	public function view($url = '')
	{
		$where['where']['url'] = $url;
		$this->data['info'] = model(__CLASS__)->read($where);
		if ( ! $this->data['info'])
		{
			show_404();
		}
		if(!$this->data['info']->public)
		{
			// lay thong tin admin
			$this->load->helper('admin');
			if(!admin_get_account_info())
				show_404();

		}
		$this->data['info'] = $this->_mod()->add_info($this->data['info']);
		$this->data['info'] = $this->_mod()->url($this->data['info']);
		// Cap nhat so luot view
		$data = array();
		$data['view'] = $this->data['info']->view + 1;
		$this->_model()->update($this->data['info']->id, $data);

		// Xu ly thong tin cua service
		page_info('title', $this->data['info']->name);
		page_info('description', $this->data['info']->description ?: $this->data['info']->name);
		page_info('keywords', $this->data['info']->keywords ?: $this->data['info']->name);

		$robots = array();
		if($this->data['info']->nofollow)
			$robots[] = 'nofollow noindex';
		page_info('robots', $robots );

		$this->data['breadcrumbs'][] = array(site_url(__CLASS__), lang(__CLASS__));
		$this->data['breadcrumbs'][] = array(current_url(), $this->data['info']->name, $this->data['info']->name);

		$this->_display();
	}

	public function download($url = '')
	{
		$result = ['label' => lang('error_file_not_exit'), 'content' => lang('error_file_not_exit')];
		$where['where']['url'] = $url;
		$this->data['info'] = model(__CLASS__)->read($where);
		if ( ! $this->data['info'])
		{
			set_output('json', json_encode($result));
		}
		if(!$this->data['info']->public)
		{
			// lay thong tin admin
			$this->load->helper('admin');
			if(!admin_get_account_info())
				set_output('json', json_encode($result));

		}

		$this->data['info'] = $this->_mod()->url($this->data['info']);
		$this->data['captcha'] 	= site_url('captcha/four');
		if ($this->input->post ()) {
			$this->load->library ( 'form_validation' );
			$this->load->helper ( 'form' );
			$return = $this->_autocheck ();
			$this->load->model('form_model');
			// Them vao data
			$data = elements ( array (
					'email',
					'name',
					'phone',
			), $this->input->post (), '' );
			$data['data'] = json_encode(elements(array('address','content'), $this->input->post(), ''));
			$data ['lang_id'] = lang_get_cur()->id;
			$data ['created'] = now ();
			$data ['ip'] = $this->input->ip_address ();
			$data['type'] =  mod('form')->config('type_download');
			$data['url'] = current_url();
			$this->form_model->create ( $data );

			// cong luot down
			$data = array();
			$data['download'] = $this->data['info']->download + 1;
			$this->_model()->update($this->data['info']->id, $data);

			// lay danh sach danh sach file
			$this->data['info']->images = model('file')->get_list_of_mod(__CLASS__, $this->data['info']->id, 'files');
			foreach ($this->data['info']->images as $row) {
				$row->image = file_get_image_from_name($row->file_name);
				$row->_size = get_info_size($row->size, 'k');
			}
			$this->data['info'] = $this->_mod()->add_info($this->data['info']);
			ob_start();
			$this->_display('_files', null);
			$result['label'] = ob_get_clean();
			$result['content'] = '';
			$result['status'] = true;
			set_output('json', json_encode($result));
		}
		ob_start();
		$this->_display('_form', null);
		$result['content'] = ob_get_clean();
		$result['label'] = lang('btn_download').': '.$this->data['info']->name;
		set_output('json', json_encode($result));
	}

	/**
	 * Tu dong kiem tra gia tri cua bien
	 */
	function _autocheck()
	{
		return $this->_set_rules();

	}
	function _check_security_code($value)
	{
		$this->load->library('captcha_library');

		if (!$this->captcha_library->check($value,'four'))
		{
			$this->form_validation->set_message(__FUNCTION__, $this->lang->line('notice_code_incorrect'));
			return FALSE;
		}

		return TRUE;
	}
	function _set_rules($params = array())
	{


		$rules 					= array();
		$this->form_validation->set_rules('email','lang:email', 'required|trim|xss_clean|valid_email');
		$this->form_validation->set_rules('name','lang:name', 'required|trim|xss_clean');
		$this->form_validation->set_rules('address','lang:address', 'trim|xss_clean');
		$this->form_validation->set_rules('phone','lang:phone', 'required|trim|xss_clean|is_natural');
		$this->form_validation->set_rules('security_code','lang:security_code','required|trim|callback__check_security_code');

		if ($this->form_validation->run() == FALSE)
		{
			echo json_encode(array('status' =>false,'label'=> validation_errors()));exit;
		}
		return false;
	}
}