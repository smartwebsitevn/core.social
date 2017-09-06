<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Read_captcha extends MY_Controller
{
	/**
	 * Lay thong tin
	 */
	public function get()
	{
		$key = $this->input->get('key');
		$key = (!$key) ? model('setting')->get('api-read_captcha-key') : $key;
		$key = (string)$key;
		
		$result = array();
		$result['complete'] = TRUE;
		$result['key_captcha'] 	= $key;
		$result['number_captcha'] = (int) lib('read_captcha')->get_left($key);
		
		$output = json_encode($result);
		set_output('json', $output);
	}
	
	/**
	 * Update thong tin
	 */
	public function edit()
	{
		// Tai cac file thanh phan
		$this->load->library('form_validation');
		$this->load->helper('form');
		// Xu ly form
		if ($this->input->post('_submit'))
		{
			// Gan dieu kien cho cac bien
			$this->form_validation->set_rules('key_captcha', 'read_captcha_key', 'required|trim|xss_clean');
			
			// Xu ly du lieu
			$result = array();
			if ($this->form_validation->run())
			{
				// Luu du lieu vao data
				$key = $this->input->post('key_captcha');
				model('setting')->set('api-read_captcha-key', $key);
				// Lay so lan doc con lai cua key
				$left = (int) lib('read_captcha')->get_left($key);
		
				// Khai bao du lieu tra ve
				$result['complete'] = TRUE;
				$result['key_captcha'] 		= $key;
				$result['number_captcha'] 	= $left;
			}
			else
			{
				$result['key_captcha'] = form_error('key');
			}
          
			
			$output = json_encode($result);
			set_output('json', $output);
		}
	}
	
}