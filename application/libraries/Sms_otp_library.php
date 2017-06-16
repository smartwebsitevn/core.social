<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Core Sms otp Library Class
 * 
 * Class xay dung cho cac payment
 * 
 * @author		hoangvantuyencnt@gmail.com
 * @version		2016-03-18
 */

// ------------------------------------------------------------------------

/**
 * Thu vien payment card
 */
class Sms_otp_library
{
	/**
	 * Goi cac sms_otp duoc yeu cau
	 */
	public function __get($key)
	{
		return t('lib')->driver('sms_otp', $key);
	}

	/**
	 * Goi method cua cac class
	 *
	 * @param string $method
	 * @param array  $args
	 * @param string $type		Kieu cua nha cung cap (send|receive)
	 * @return mixed
	 */
	public function call($method, array $args = array(), $type = 'send')
	{
	    $provider = $this->get_provider($type);
	
	    return call_user_func_array(array($this->{$provider}, $method), $args);
	}
	
	/**
	 * Lay nha cung cap hien tai
	 *
	 * @param string $type		Kieu cua nha cung cap (send|receive)
	 * @return string
	 */
	public function get_provider($type = 'send')
	{
	    $provider = model('sms_otp')->get_default();

	    return $provider;
	}
	

	/**
	 * Lay so du cua merchant
	 *
	 * @see MY_Sms::get_balance()
	 */
	public function get_balance()
	{
	    return $this->call(__FUNCTION__, func_get_args(), 'send');
	}
	

	/**
	 * Lay so du cua merchant
	 *
	 * @see MY_Sms::send()
	 */
     public function send($phone, $msg, &$result = '')
     {
		 $provider = $this->get_provider('send');

		 return $this->{$provider}->send($phone, $msg, $result);
     }
}

// ------------------------------------------------------------------------

/**
 * Class xay dung cua cac sms_otp
 */
Class MY_sms_otp {
	
	// Bien luu thong tin gui den view
	public $data = array();
	
	// Ma code cua sms_otp
	public $code = '';
	
	// Setting cua sms_otp
	public $setting = array();
	public $setting_default = array();
	
	
/*
 * ------------------------------------------------------
 *  Main handle
 * ------------------------------------------------------
 */
	/**
	 * Cho phep su dung cac thuoc tinh cua controller
	 */
	public function __get($key)
	{
		return t($key);
	}
	
	/**
	 * Ham khoi dong cho cac sms_otp
	 */
	function __construct()
	{
		// Tai cac file thanh phan
		$this->load->language('sms_otp/' . $this->code);
		
		// Them cac bien setting default vao setting
		$this->setting = array_merge($this->setting_default, $this->setting);
		
		// Cap nhat setting neu sms_otp da duoc cai dat
		if (model('sms_otp')->installed($this->code))
		{
			// Lay setting trong data
			$setting_data = model('sms_otp')->get_setting($this->code);
			
			// Cap nhat gia tri tu setting trong data
			$this->setting = extend($this->setting, $setting_data);
			
			// Neu setting trong data khac voi setting hien tai thi cap nhat setting hien tai vao data
			if (count($setting_data) != count($this->setting))
			{
				model('sms_otp')->set_setting($this->code, $this->setting);
			}
		}
	}
	
	
/*
 * ------------------------------------------------------
 *  Setting handle
 * ------------------------------------------------------
 */
	/**
	 * Chinh sua setting cua payment
	 */
	function setting()
	{
		// Tai cac file thanh phan
		$this->load->model('currency_model');
		$this->load->library('form_validation');
		$this->load->helper('form');
		
		// Lay cac bien cai dat
		$params = array_keys($this->setting);
		
		
		// Tu dong kiem tra gia tri cua 1 bien
		$param = $this->input->post('_autocheck');
		if ($param)
		{
			$this->_setting_autocheck($param);
		}
		
		// Xu ly form
		if ($this->input->post('_submit'))
		{
			// Gan dieu kien cho cac bien
			$this->_setting_set_rules($params);
			
			// Xu ly du lieu
			$result = array();
			if ($this->form_validation->run())
			{
				// Lay gia tri setting tu form
				foreach ($params as $p)
				{
					$this->setting[$p] = $this->input->post($p);
				}
				
				// Goi ham xu ly setting cua payment
				$this->_setting_handle();
				
				// Luu setting vao data
				$setting_data = array();
				foreach ($params as $p)
				{
					$setting_data[$p] = $this->setting[$p];
				}
				model('sms_otp')->set_setting($this->code, $setting_data);
				
				// Khai bao ket qua tra ve
				$result['complete'] = TRUE;
				$result['location'] = admin_url('sms_otp');
				set_message(lang('notice_update_success'));
			}
			else
			{
				foreach ($params as $param)
				{
					$result[$param] = form_error($param);
				}
			}
			
			$output = json_encode($result);
			set_output('json', $output);
		}
		
		
		// Loai bo cac bien mac dinh ra khoi params truoc khi gui den view
		$params_default = array_keys($this->setting_default);
		foreach ($params as $k => $p)
		{
			if (in_array($p, $params_default))
			{
				unset($params[$k]);
			}
		}
		
		// Luu bien gui den view
		$this->data['action'] 	= current_url();
		$this->data['params'] 	= $params;
		$this->data['code'] 	= $this->code;
		$this->data['setting'] 	= $this->setting;
		
		return $this->data;
	}
	
	/**
	 * Gan dieu kien cho cac bien
	 */
	protected function _setting_set_rules($params = array())
	{
		// Thiet lap dieu kien cho cac bien mac dinh
		$rules = array();
		
		// Lay dieu kien cho cac bien cua payment
		$rules_payment = $this->_setting_get_rules();
		
		$rules['phone'] = array("phone", 'required');
		$rules['smsMessage'] = array("smsMessage", 'required');
		   
		// Gop cac dieu kien
		$rules = array_merge($rules, $rules_payment);
		
		// Gan dieu kien
		$this->form_validation->set_rules_params($params, $rules);
	}
	
	/**
	 * Lay dieu kien gan cho cac bien
	 */
	protected function _setting_get_rules()
	{
		$params_default = array_keys($this->setting_default);
		
		$rules = array();
		foreach ($this->setting as $p => $v)
		{
			if (!in_array($p, $params_default))
			{
				$rules[$p] = array("sms_otp_{$this->code}_{$p}", 'required');
			}
		}
		
		return $rules;
	}
	
	/**
	 * Ham xu ly setting
	 */
	protected function _setting_handle() {}
	
	/**
	 * Tu dong kiem tra gia tri cua bien
	 */
	protected function _setting_autocheck($param)
	{
		$this->_setting_set_rules($param);
		
		$result = array();
		$result['accept'] 	= $this->form_validation->run();
		$result['error'] 	= form_error($param);
		
		$output = json_encode($result);
		set_output('json', $output);
	}
	
	
	/**
	 * Duoc goi khi cai dat
	 */
	function install(){}
	
	/**
	 * Duoc goi khi go bo
	 */
	function uninstall(){}

	/**
	 * Gui sms
	 *
	 * @param string $phone		So dien thoai nhan sms
	 * @param string $msg		Noi dung sms
	 * @param string $result	Ket qua
	 * @return bool
	 */
	public function send($phone, $msg, &$result = '')
	{
	    return TRUE;
	}
	
	/**
	 * Test ket noi
	 */
	public function test()
	{
	    // Tai cac file thanh phan
	    $this->load->model('currency_model');
	    $this->load->library('form_validation');
	    $this->load->helper('form');
	    
	    // Lay cac bien cai dat
	    $params = array('phone', 'smsMessage');
	    
	    
	    // Tu dong kiem tra gia tri cua 1 bien
	    $param = $this->input->post('_autocheck');
	    if ($param)
	    {
	        $this->_setting_autocheck($param);
	    }
	    
	    // Xu ly form
	    if ($this->input->post('_submit'))
	    {
	        // Gan dieu kien cho cac bien
	        $this->_setting_set_rules($params);
	   
	        // Xu ly du lieu
	        $result = array();
	        if ($this->form_validation->run())
	        {
	            $phone = $this->input->post('phone');
	            $smsMessage = $this->input->post('smsMessage');
	            $result_sms = '';
	            
	            $data = array();
	            $data['type']    = 'sms_otp';
	            $data['mod']     = 'test';
	            $data['phone']   = $phone;
	            $data['message'] = $smsMessage; 
	            $data['status']  = config('sms_status_pending', 'main');
	            
	            $sms_otp_log_id = 0;
	            model('sms_otp_log')->create($data, $sms_otp_log_id);
	            $status = $this->sms_otp->{$this->code}->send($phone, $smsMessage, $result_sms);
	            
	            $data = array();
	            if($status == true)
	            {
	                $data['status'] = config('sms_status_completed', 'main');
	            }else{
	                $data['status'] = config('sms_status_failed', 'main');
	            }
	            $data['result']  = $result_sms;
	            model('sms_otp_log')->update($sms_otp_log_id, $data);
	            
	            
	            // Khai bao ket qua tra ve
	            $result['complete'] = TRUE;
	            $result['location'] = admin_url('sms_otp_log');
	        }
	        else
	        {
	            foreach ($params as $param)
	            {
	                $result[$param] = form_error($param);
	            }
	        }
	        	
	        $output = json_encode($result);
	        set_output('json', $output);
	    }
	   
	    // Luu bien gui den view
	    $this->data['action'] 	= current_url();
	    $this->data['code'] 	= $this->code;
	    
	    view('tpl::sms_otp/test', $this->data);
	}
	
	
	
	/**
	 * Lay so du cua merchant
	 *
	 * @return float
	 */
	public function get_balance()
	{
	    return 0;
	}
}
