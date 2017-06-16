<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Core Payment Card Library Class
 * 
 * Class xay dung cho cac payment
 * 
 * @author		sontung0@gmail.com
 * @version		2015-04-03
 */

// ------------------------------------------------------------------------

/**
 * Thu vien payment card
 */
class Payment_card_library
{
	/**
	 * Goi cac payment_card duoc yeu cau
	 */
	public function __get($key)
	{
		return t('lib')->driver('payment_card', $key);
	}


	public function card_charging($type_id,$code,$serial,&$output=NULL)
	{
		$type = $this->_get_card_type($type_id);
		if(!$type){
			$output= lang('notice_value_invalid');
			return false;
		}
		$ip = t('input')->ip_address();

		// Ip hien tai da bi block
		if (model('ip_block')->check($ip)) {
			$output= lang('notice_ip_blocked', $ip);
			return false;
		}

		// Gui request den cac providers
		$input =	compact('type_id', 'type', 'code', 'serial');

		$providers = array_filter([$type->provider,$type->provider_sub]);
		list($status, $result, $provider) = $this->_request_check_card($providers, $input);

		//echo '<br> status:',pr($status,0);
		//echo '<br> result:',pr($result,1);
		// Xu ly so lan check card
		$this->_handle_count_check($status);

		// Neu card khong hop le
		if (!$status) {
			if(is_array($result)){
				$result = json_encode($result);
			}
			$message = array($result);
			if ($m = $this->_make_warning_block_ip()) {
				$message[] = $m;
			}

			$output= implode('<br>', $message);
			return false;
		}

		$output = array_merge($input, $result, compact('provider'));

		return true;
	}

	/**
	 * Gui yeu cau check card
	 *
	 * @param array $providers
	 * @param array $input
	 * @return array [$status, $result, $provider]
	 */
	protected function _request_check_card(array $providers, array $input)
	{
		$status = false;
		$result = null;
		$provider = null;

		foreach ($providers as $provider) {
			list($status, $result) = $this->_request_provider_check_card($provider, $input);

			if ($status) break;
		}

		return [$status, $result, $provider];
	}

	/**
	 * Gui yeu cau den provider check card
	 *
	 * @param string $provider
	 * @param array $input
	 * @return array
	 */
	protected function _request_provider_check_card($provider, array $input)
	{
		$log_id = $this->_log($provider, $input);

		list($status, $result) = $this->_perform_check_card(
			$provider,
			$input['type']->key,
			$input['code'],
			$input['serial']
		);

		$this->_log_result($log_id, $status, $result);

		return [$status, $result];
	}

	/**
	 * Luu log
	 *
	 * @param string $provider
	 * @param array $input
	 * @return int
	 */
	protected function _log($provider, array $input)
	{
		$log_id = 0;

		model('deposit_card_log')->create([
			'provider' => $provider,
			'type' => $input['type']->key,
			'code' => $input['code'],
			'serial' => $input['serial'],
			'status' => mod('deposit_card_log')->status('pending'),
			'user_id' => user_get_account_info()->id,
			'ip' => t('input')->ip_address(),
		], $log_id);

		return $log_id;
	}

	/**
	 * Luu log result
	 *
	 * @param int $log_id
	 * @param bool $api_status
	 * @param string|array $api_result
	 */
	protected function _log_result($log_id, $api_status, $api_result)
	{
		if ($api_status) {
			model('deposit_card_log')->update($log_id, [
				'amount' => $api_result['amount'],
				'status' => mod('deposit_card_log')->status('completed'),
				'message' => 'success',
				'result' => json_encode($api_result['data']),
			]);
		} else {
			model('deposit_card_log')->update($log_id, [
				'status' => mod('deposit_card_log')->status('failed'),
				'message' => json_encode($api_result),
			]);
		}
	}

	/**
	 * Tao noi dung canh bao block ip
	 *
	 * @return string
	 */
	protected function _make_warning_block_ip()
	{
		$count_max = mod('deposit_card')->setting('fail_count_max');

		if (!$count_max) return;

		$max = mod('deposit_card')->setting('fail_count_max');
		$minute = mod('deposit_card')->setting('fail_block_timeout');

		return lang('notice_warning_block_ip', compact('max', 'minute'));
	}

	/**
	 * Lay thong tin card type
	 *
	 * @param int $id
	 * @return false|object
	 */
	protected function _get_card_type($id)
	{
		return model('card_type')->get_info_rule(array(
			'id' => $id,
			'status' => 1,
		));
	}

	/**
	 * Lay danh sach card type
	 */
	protected function _get_card_types()
	{
		return mod('card_type')->get_list(array('status' => 1));
	}
	/**
	 * Goi den api kiem tra card
	 *
	 * @param string $type
	 * @param string $code
	 * @param string $serial
	 * @return array
	 */
	protected function _perform_check_card($provider, $type, $code, $serial)
	{
		$api_result = array();
		$api_status = t('payment_card')->$provider->check($type, $code, $serial, $api_result);

		/*$api_status = true;
        $api_result['amount'] = 110000;
        $api_result['data'] = t('input')->post();*/

		/* $api_status = false;
        $api_result = 'error'; */

		return [$api_status, $api_result];
	}

	/**
	 * Xu ly so lan check card
	 *
	 * @param bool $api_status
	 */
	protected function _handle_count_check($api_status)
	{
		if (!$api_status) {
			$this->_handle_count_fail();
		} else {
			if (model('ip')->action_count_get('deposit_card_fail')) {
				model('ip')->action_count_set('deposit_card_fail', 0);
			}
		}
	}

	/**
	 * Xu ly so lan nhap sai card
	 */
	protected function _handle_count_fail()
	{
		$count_max = mod('deposit_card')->setting('fail_count_max');
		$block_timeout = mod('deposit_card')->setting('fail_block_timeout') * 60;

		if (!$count_max) return;

		$count = model('ip')->action_count_change('deposit_card_fail', 1);

		if ($count >= $count_max) {
			$ip = t('input')->ip_address();

			model('ip_block')->set($ip, $block_timeout);
			model('ip')->action_count_set('deposit_card_fail', 0);
		}
	}

	/**
	 * Tao Lich su
	 */
	protected function _history()
	{
		$this->_list([
			'input' => [
				'select' => 'deposit_card.*, tran.created',
				'where' => [
					'tran.user_id' => user_get_account_info()->id,
					'deposit_card.status' => mod('order')->status('completed'),
				],
			],
			'page_size' => 10,
			'display' => false,
		]);
	}


}

// ------------------------------------------------------------------------

/**
 * Class xay dung cua cac payment_card
 */
Class MY_Payment_card {
	
	// Bien luu thong tin gui den view
	public $data = array();
	
	// Ma code cua payment_card
	public $code = '';
	
	// Setting cua payment_card
	public $setting = array();
	public $setting_cur = array();
	public $setting_default = array('default' => '');
	
	
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
	 * Ham khoi dong cho cac payment_card
	 */
	function __construct()
	{
		// Tai cac file thanh phan
		$this->load->language('payment_card/' . $this->code);
		
		// Them cac bien setting default vao setting
		$this->setting_data = array_merge($this->setting_default, $this->setting);
		
		// Cap nhat setting neu payment_card da duoc cai dat
		if (model('payment_card')->installed($this->code))
		{
			// Lay setting trong data
			$setting_data = model('payment_card')->get_setting($this->code);
			$this->setting_data = $setting_data;
			// Cap nhat gia tri tu setting trong data
			foreach ($setting_data as $key => $val)
			{
			    if(!is_array($val))
			    {
			        $this->setting_data = array($this->setting_data);
			    }
			    break;
			}
			//lay tai khoan ket noi	
			$this->_get_setting_cur();
		}
	}
	
	/*
	 * Lấy cấu hình tài khoản kết nối đang được sử dụng
	 */
	private function _get_setting_cur()
	{ 
	    foreach ($this->setting_data as $key => $account)
	    {
	        if(is_array($account))
	        {
	            foreach ($account as $p => $val)
	            {
	                if($p == 'default' && $key == $val)
	                {
	                    $this->setting_cur = $this->setting_data[$key];
	                }
	            }
	        }
	    }
	    
	    //neu k co tai khoan mac dinh thi lay tai khoan dau tien
	    if(empty($this->setting_cur) && isset($this->setting_data[1]))
	    {
	        $this->setting_cur = $this->setting_data[1];
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
		$params = array_keys(array_merge($this->setting_default, $this->setting));
	    
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
			    // Goi ham xu ly setting cua payment
			    $this->_setting_handle();
			    
			    //lay chiet khau goc cua nha cung cap
			    $types = $this->get_types();
			    $discount = array();
			    
				// Lay gia tri setting tu form
			    $setting_data = array();
			    $default = $this->input->post('default');
			    unset($params[0]);
			    $total = 0;
				foreach ($params as $p => $v)
				{
				    $val = $this->input->post($v);
				    $total = count($val);
				    $values[$v] = $val;
				}
				$keys = array_keys($values);
				for ($i = 1;$i<= $total;$i++)
				{
				    $d = array();
				    foreach ($keys as $key)
				    {
				        if(isset($values[$key][$i]) && $values[$key][$i])
				        {
				            $d['default'] = $default;
				            $d[$key] = $values[$key][$i];
				        }  
				    }
				    if(!empty($d))
				    {
				        $setting_data[$i] = $d;
				    }
				    
				    foreach ($types as $type)
				    {
				        $discount[$i][$type] = $this->input->post('discount_'.$type.'_'.$i);
				    }
				}
				
				model('payment_card')->set_setting($this->code, $setting_data);	
				model('payment_card')->set_setting('discount_'.$this->code, $discount);
				
				// Khai bao ket qua tra ve
				$result['complete'] = TRUE;
				$result['location'] = admin_url('payment_card');
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
		/*
		$params_default = array_keys($this->setting_default);
		foreach ($params as $k => $p)
		{
			if (in_array($p, $params_default))
			{
				unset($params[$k]);
			}
		}
		*/
		
		$discounts = model('payment_card')->get_setting('discount_'.$this->code);
		$this->data['discounts'] = $discounts;
		
		// Luu bien gui den view
		$this->data['action'] 	= current_url();
		$this->data['params'] 	= $params;
		
		$this->data['code'] 	= $this->code;
		$this->data['setting'] 	= $this->setting_data;
		return $this->data;
	}
	
	/**
	 * Lấy chiết khấu gốc từ nhà cung cấp
	 */
	public function get_discount()
	{
	    $discounts = model('payment_card')->get_setting('discount_'.$this->code);
	    $provider_account = $this->setting_cur;
	    if(!isset($provider_account['default']))
	    {
	        return false;
	    }
	    $provider_account_id = $provider_account['default'];
	    if(!isset($discounts[$provider_account_id]))
	    {
	        return false;
	    }
	    $discount = $discounts[$provider_account_id];
	    return $discount;
	}
	
	/**
	 * Gan dieu kien cho cac bien
	 */
	protected function _setting_set_rules($params = array())
	{
		// Thiet lap dieu kien cho cac bien mac dinh
		$rules = array();
	    foreach ($params as $p => $v)
		{
			if ($v != 'default')
			{
			    $values = $this->input->post($v);
			    foreach ($values as $k => $val)
			    {
			        if(!$val) unset($values[$k]);
			    }
			    if(empty($values))
			    {
			        $rules[$v] = array("payment_card_{$this->code}_{$v}", 'required');
			    }
			}
		}
		$rules['default'] = array("payment_card_{$this->code}_default",'required');
		
		//lay chiet khau goc cua nha cung cap
		$types = $this->get_types();
		foreach ($types as $type)
		{
		    $rules['discount_'.$type] = array($type, 'required');
		}
		
		// Lay dieu kien cho cac bien cua payment
		//$rules_payment = $this->_setting_get_rules();
		
		// Gop cac dieu kien
		//$rules = array_merge($rules, $rules_payment);
		
		// Gan dieu kien
		$this->form_validation->set_rules_params($params, $rules);
	}
    
	/**
	 * Lay dieu kien gan cho cac bien
	 */
	function _check_default($value)
	{
	    $params = array_keys(array_merge($this->setting_default, $this->setting));
	    foreach ($params as $param)
	    {
	        if(!$this->input->post($param[$value]))
	        {
	            $this->form_validation->set_message(__FUNCTION__, lang('required'));
	            return false;
	        }
	    }
	    return true;
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
				$rules[$p] = array("payment_card_{$this->code}_{$p}", 'required');
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
	 * Thuc hien kiem tra the
	 *
	 * @param string $type
	 * @param string $code
	 * @param string $serial
	 * @param array  $output
	 * @return boolean
	 */
	public function check($type, $code, $serial, &$output = array())
	{
		return false;
	}

	/**
	 * Lay cac loai the ho tro
	 * 
	 * @return array
	 */
	public function get_types()
	{
		return array();
	}
	
	/**
	 * Test ket noi
	 */
	public function test()
	{
	}
	
}
