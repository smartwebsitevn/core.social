<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Send_email extends MY_Controller
{
	/**
	 * Ham khoi dong
	 */
	public function __construct()
	{
		parent::__construct();

		$this->lang->load('admin/'.$this->_get_mod());
	}
	
	/**
	 * Lay danh sach cac bien
	 * 
	 * @return array
	 */
	protected function _get_params()
	{
		return array('to', 'subject', 'message');
	}
	
	/**
	 * Gan dieu kien cho cac bien
	 */
	protected function _set_rules($params)
	{
		$rules = array();
		$rules['to'] 			= array('email_to', 'required|trim|callback__check_to');
		$rules['subject'] 		= array('subject', 'required|trim|xss_clean');
		$rules['message'] 		= array('message', 'required|trim|xss_clean');
		
		$this->form_validation->set_rules_params($params, $rules);
	}

	/**
	 * Kiem tra image
	 */
	public function _check_to()
	{
		if ( ! count($this->_get_input('to')))
		{
			$this->form_validation->set_message(__FUNCTION__, lang('notice_value_invalid'));
			return FALSE;
		}
		
		return TRUE;
	}
	
	/**
	 * Lay input
	 */
	protected function _get_input($param = '')
	{
	    $data = array();
	    foreach ($this->_get_params() as $p)
	    {
	        $data[$p] = $this->input->post($p);
	    }
	    
	    $data['to'] = $this->_make_input_to( $data['to']);
		
	    return ($param) ? $data[$param] : $data;
	}
	
	/**
	 * Xu ly gia tri cua input To
	 * 
	 * @param string $input
	 * @return array
	 */
	protected function _make_input_to($input)
	{
		$input = explode(',', $input);
		
		$input = array_where($input, function($key, $value)
		{
			return filter_var($value, FILTER_VALIDATE_EMAIL);
		});
		
		return $input;
	}
	
	/**
	 * Them moi
	 */
	public function index()
	{
		$form = array();
		
		$form['validation']['params'] = $this->_get_params();
		
		$form['submit'] = function($params)
		{
			$data = $this->_get_input();
			
			mod('email')->to($data['to'], $data['subject'], $data['message']);
			
			set_message(lang('notice_send_success'));
		};
		
		$form['form'] = function()
		{
			$this->data['input'] = array_only((array) t('input')->get(), $this->_get_params());
			$this->data['url_search'] = admin_url('user/ac/email');
			
			$this->_display();
		};
		
		$this->_form($form);
	}
	
}