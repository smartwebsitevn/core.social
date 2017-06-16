<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Api extends MY_Controller {
	
	var $data = array();
	function __construct()
	{
		parent::__construct();
		
		$this->data = $this->input->post();
		// Kiem tra code
		$code = isset($this->data['api_key']) ? $this->data['api_key'] : '';
		if ($code != 'd7fc60f2d45f538b849b64bde310e79b')
		{
			$this->writeFileLog('log_api.txt', @serialize($_REQUEST));
			
			$this->_get_error('This api key is not allowed to access');
		}
	}
	
/*
 * ------------------------------------------------------
 *  Main user
 * ------------------------------------------------------
 */
	function get_total_ip()
	{
	    $user_id   = isset($this->data['user_id']) ? (string)$this->data['user_id'] : '';
	    
	    //kiem tra thanh vien
	    $user_id = intval($user_id);
	    $user =  $this->user_model->get_info($user_id);
	    if(!$user)
	    {
	        $this->_get_error('Không tồn tại thành viên này trên hệ thống, vui lòng kiểm tra lại');
	    }
	    
	    //kiem tra IP
	    $this->load->model('login_history_model');
	    $input = array();
	    $input['where'] = array('user_id' => $user->id, 'is_app' => 1);
	    $input['select'] = 'login_history.ip';
	    $user_login_historys = $this->login_history_model->get_list($input);
	    $total_ip = 0;
	    $ips = array();
	    foreach ($user_login_historys as $row)
	    {
	        if(!isset($ips[$row->id]))
	        {
	            $ips[$row->id] = 1;
	            $total_ip += 1;
	        }
	    }
	    
	    $result = array();
	    $result['Status']   = 1;
	    $result['total_ip'] = $total_ip;
	    $this->_set_output($result);
	}
	
	/*
	 * Thành viên đăng nhập
	*/
	function UserLogin()
	{
		$this->load->model('user_model');
		$this->load->helper('email');
		$this->lang->load('site/user');
		
		// Lay thong tin dang nhap
		$email     = isset($this->data['email']) ? (string)$this->data['email'] : '';
		$password  = isset($this->data['password']) ? (string)$this->data['password'] : '';
		
		
		// Kiem ra du lieu dau vao
		if (!$email || !$password || !valid_email($email))
		{
			$this->_get_error(lang('notice_login_false'));
		}
		
		// Lay thong tin thanh vien tu email
		$where = array();
		$where['email'] = $email;
		$user = $this->user_model->get_info_rule($where);
		
		// Neu khong ton tai user tuong ung
		if (!$user)
		{
			$this->_get_error(lang('notice_login_false'));
		}
		
		// Tai khoan bi khoa
		if ($user->blocked == config('verify_yes', 'main'))
		{
			$this->_get_error(lang('notice_account_blocked'));
		}
		
		// Neu dang nhap khong thanh cong
		if (!user_login($email, $password))
		{
			$this->_get_error(lang('notice_login_false'));
		}
		
		// Neu dang nhap thanh cong
		else
		{
		    //kiem tra IP
		  
		    $this->load->model('login_history_model');
		    /*
		    $where = array('user_id' => $user->id, 'is_app' => 1);
		    $user_login_history = $this->login_history_model->get_info_rule($where);
		    if(isset($user_login_history->ip))
		    {
		        //neu khac IP va da truy cap chua duoc 2 tieng
    		    if($user_login_history->ip != $this->input->ip_address()
    		        && ($user_login_history->time >= (now() - 10*60)) )
    		    {
    		        //$this->_get_error('Lần truy cập gần nhất của tài khoản này là IP khác');
    		    }
		    }
		    */
		
			$result = array();
			$result['Status']  = 1;
			$result['Message'] = 'Đăng nhập thành công';
			
			$data = array();
			$data['user_id']    = $user->id;
			$data['user_name']  = $user->name;
			$data['user_email'] = $user->email;
			$data['date_exp']   = get_date($user->expire);
			$data['is_expire']  = ($user->expire < now()) ? 1 : 0;
			$data['url_renew']  = site_url('renew');
			$result['data']     = $data;
			
			// Luu vao lich su dang nhap
			$this->load->model('login_history_model');
			$data = array();
			$data['is_admin'] 	= config('verify_no', 'main');
			$data['user_id'] 	= $user->id;
			$data['ip'] 		= $this->input->ip_address();
			$data['time'] 		= now();
			$data['is_app']     = 1;
			$this->login_history_model->create($data);
				
			$this->_set_output($result);
		}
	}
	
	
	/*
	 * Action: UserRegister (Thành viên đăng ký) 
	 * @name: Tên thành viên
	 * @phone: Số điện thoại của thành viên
	 * @email: Email của thành viên,dùng làm tài khoản đăng nhập
	 * @password: Mật khẩu đăng nhập của thành viên
	*/
	function UserRegister()
	{
		$this->load->model('user_model');
		$this->load->helper('email');
		$this->lang->load('site/user');
	
		// Lay thong tin dang nhap
		$name      = isset($this->data['name']) ? (string)$this->data['name'] : '';
		$phone     = isset($this->data['phone']) ? (string)$this->data['phone'] : '';
		$email     = isset($this->data['email']) ? (string)$this->data['email'] : '';
		$password  = isset($this->data['password']) ? (string)$this->data['password'] : '';
	    
	    
		// Kiem ra du lieu dau vao
		if (!$email || !$password || !$phone || !$name)
		{
			$this->_get_error('Không đủ dữ liệu,vui lòng kiểm tra lại');
		}
		if( !valid_email($email))
		{
			$this->_get_error('Email không hợp lệ,vui lòng kiểm tra lại');
		}
		if( !$this->_check_email($email))
		{
			$this->_get_error('Email đã được sử dụng,vui lòng kiểm tra lại');
		}
		if(strlen($password) < 6)
		{
			$this->_get_error('Mật khẩu phải ít khấu 6 ký tự,vui lòng kiểm tra lại');
		}
		
		$result = array();
		$result['Status']  = 1;
		$result['Message'] = 'Đăng ký thành công';
			
		// Xu ly password
		$password = security_encode_password($password, strtolower($email));
		// Luu vao data
		$data         			= array();
		$data['email']			= $email;
		$data['password']		= $password;
		$data['name']			= $name;
		$data['phone']			= $phone;
		$data['created'] 		= now();
		$this->user_model->create($data);
			
		$result['data']    = 'NULL';	
		$this->_set_output($result);
	}
	

	/*
	* Action: UserUpdate (Cập nhật tài khoản)
	* @name: Tên thành viên
	* @phone: Số điện thoại của thành viên
	* @password: Mật khẩu đăng nhập của thành viên, nếu không đổi mật khẩu thì không gửi biến này qua
	* @user_id:  Id thành viên cập nhật thông tin
	*/
	function UserUpdate()
	{
		$this->load->model('user_model');
		$this->load->helper('email');
		$this->lang->load('site/user');
	
		// Lay thong tin dang nhap
		$name      = isset($this->data['name']) ? (string)$this->data['name'] : '';
		$phone     = isset($this->data['phone']) ? (string)$this->data['phone'] : '';
		$password  = isset($this->data['password']) ? (string)$this->data['password'] : false;
		$user_id   = isset($this->data['user_id']) ? (string)$this->data['user_id'] : '';
		
		//kiem tra thanh vien
		$user_id = intval($user_id);
		$user =  $this->user_model->get_info($user_id);
		if(!$user)
		{
			$this->_get_error('Không tồn tại thành viên này trên hệ thống,vui lòng kiểm tra lại');
		}
		
		// Kiem ra du lieu dau vao
		if (!$phone && !$name && !$password)
		{
			$this->_get_error('Không đủ dữ liệu,vui lòng kiểm tra lại');
		}
		
		if($password && strlen($password) < 6)
		{
			$this->_get_error('Mật khẩu phải ít khấu 6 ký tự,vui lòng kiểm tra lại');
		}
	
		$result = array();
		$result['Status']  = 1;
		$result['Message'] = 'Cập nhật tài khoản thành công';

		// Luu vao data
		$data         			= array();
		if($password)
		{
		   // Xu ly password
		   $password = security_encode_password($password, strtolower($user->email));
		   $data['password']	= $password;
		}
		if($name)
		{
	    	$data['name']			= $name;
		}
		if($phone)
		{
		    $data['phone']			= $phone;
		}
		$this->user_model->update($user_id,$data);
			
		$user =  $this->user_model->get_info($user->id); 
		$data = array();
		$data['user_id']    = $user->id;
		$data['user_name']  = $user->name;
		$data['user_email'] = $user->email;
		$data['date_exp']   = get_date($user->expire);
		$data['is_expire']  = ($user->expire < now()) ? 1 : 0;
		$data['url_renew']  = site_url('renew');
		$result['data']    = $data;
			
		$this->_set_output($result);
	}
	

	/**
	 * Quen mat khau
	 */
	function forgot()
	{
		$email     = isset($this->data['email']) ? (string)$this->data['email'] : '';
		
		// Lay thong tin cua thanh vien
		$this->load->model('user_model');
		$where = array();
		$where['email'] = $email;
		$user = $this->user_model->get_info_rule($where);
		if(!$user)
		{
			$this->_get_error('Email không tồn tại');
		}
		// Gui email thong bao
		$this->load->model('email_model');
		$email_params = array();
		$email_params['user_name']  = $user->name;
		$email_params['url_forgot'] = $this->_action_create_url($user->id, 'forgot');
		$this->email_model->send('user_forgot_password', $user->email, $email_params);
	    
		$result = array();
		$result['Status']  = 1;
		$result['Message'] = 'Gửi email thành công';
		
		$data = NULL;
		$result['data']    = $data;
		$this->_set_output($result);
		
	}
	
	/**
	 * Tao url action cua thanh vien
	 */
	function _action_create_url($id, $act)
	{
		$params = array();
		$params['id'] 	= $id;
		$params['act'] 	= $act;
		$params['exp'] 	= now() + config('url_action_expire', 'main');
	
		$query = security_create_query($params, 'user');
		$url = site_url('user/action').'?'.$query;
	
		return $url;
	}
	
	
	/**
	 * Kiem tra email nay da duoc su dung chua
	 */
	function _check_email($value)
	{
		$where = array();
		$where['email'] = $value;
		$id = $this->user_model->get_id($where);
	
		if ($id)
		{
			return FALSE;
		}
	
		return TRUE;
	}
	
/*
* ------------------------------------------------------
*  Renew
* ------------------------------------------------------
*/
	/**
	 * Action: Renew_sms (Gia han bằng tin nhắn sms)
	 */
	function Renew_sms()
	{
	    //ket qua tra ve
	    $result = array();
	    $result['Status']  = 1;
	    $result['Message'] = 'Gia hạn sms';
	    $data = array();
	    $data['viettel']   = 'mw 30000 nhd nap ID';
	    $data['mobi_vina'] = 'mw nhd nap30 ID';
	    $data['guitoi']  = '9029';
	    $data['info'] = '- Số cuối cùng của tin nhắn chính là ID tài khoản của bạn.
        - Đối với mạng Viettel 30000 là số tiền.
        - Đối với mạng Vina Mobile 30 là số tiền tương ứng với 30 nghìn.';
	    $result['data']    = $data;
	     
	    $this->_set_output($result);
	}
	
	
	/*
	 * Action: PlanPremium (List cac goi premium)
	*/
	function PlanPremium()
	{
		// Lay thoi han xem phim
		$premium_price = array();
		$setting_premium_price = $this->setting_model->get('config-premium_price');
		foreach ($setting_premium_price as $amount => $day)
		{
			$row = new stdClass();
			$row->amount 	= $amount;
			$row->_amount 	= currency_format_amount($amount);
			$row->day 		= $day;
			$row->month 	= ($day && !fmod($day, 30)) ? $day/30 : 0;
			$premium_price[] = $row;
		}
	
		// lay thoi han xem phim SMS
		$sms_price = array();
		$sms_price_price = $this->setting_model->get('config-sms_price');
		foreach ($sms_price_price as $amount => $day)
		{
			$row = new stdClass();
			$row->amount 	= $amount;
			$row->_amount 	= currency_format_amount($amount);
			$row->day 		= $day;
			$row->month 	= ($day && !fmod($day, 30)) ? $day/30 : 0;
			$sms_price[] = $row;
		}
	
		$result = array();
		$result['Status']  = 1;
		$result['Message'] = 'Thong tin cac goi gia han';
	
		$data = array();
		$data['premium_price'] = $premium_price;
		$data['sms_price']     = $sms_price;
	
		$result['data']    = $data;
			
		$this->_set_output($result);
	}
	
	/**
	 * Action: Renew_card (Gia han ngay xem phim bằng thẻ cào)
	 * @card_type: loại thẻ cào, gốm các giá trị array('mobi', 'vina', 'viettel');
	 * @card_pin: Mã Pin
	 * @card_serial: Mã serial
	 * @user_id:  Id thành viên gia hạn
	 */
	function Renew_card()
	{
		// Tai cac file thanh phan
		$this->load->helper('tran');
		$this->load->model('tran_model');
		$this->load->model('user_model');
		$this->lang->load('site/tran');
		$this->lang->load('site/payment');
		
		// Lay thong tin card
		$card_type    = isset($this->data['card_type']) ? (string)$this->data['card_type'] : '';
		$card_pin     = isset($this->data['card_pin']) ? (string)$this->data['card_pin'] : '';
		$card_serial  = isset($this->data['card_serial']) ? (string)$this->data['card_serial'] : '';
		$user_id      = isset($this->data['user_id']) ? (string)$this->data['user_id'] : '';
		
		// Kiem ra du lieu dau vao
		if (!$card_type || !$card_pin || !$card_serial || !$user_id)
		{
			$this->_get_error('Không đủ dữ liệu,vui lòng kiểm tra lại');
		}
		if(!$this->_renew_check_card_type($card_type))
		{
			$this->_get_error('Không tồn tại loại thẻ cào này,vui lòng kiểm tra lại');
		}
		//kiem tra thanh vien
		$user_id = intval($user_id);
		$user =  $this->user_model->get_info($user_id);
		if(!$user)
		{
			$this->_get_error('Không tồn tại thành viên này trên hệ thống,vui lòng kiểm tra lại');
		}
		$user_expire = $user->expire;
		
		// Lay payment card dang su dung
		$card_payment = config('card_payment', 'main');
		
		// Thuc hien kiem tra card
		$api_output = '';
		$api_result = $this->payment_card->{$card_payment}->card($card_type, $card_pin, $card_serial, $user_id, $api_output);
		
		// Neu thanh cong
		if ($api_result === TRUE)
		{
			// Lay so tien da thanh toan
			$amount = $api_output['amount'];
		
			// Lay so ngay gia han tuong ung voi amount
			$days_time = array();
			$days = $this->_renew_get_days($amount, $days_time);
		
			// Neu khong ton tai so ngay
			if (!$days)
			{
				$this->_get_error(lang('notice_card_amount_error'));
			}
		
			// Neu ton tai ngay
			else
			{
				// Tinh ngay het han cho user
				$expire = max($user_expire, now());
				$expire = add_time($expire, $days_time);
		
				// Gia han cho user
				$data = array();
				$data['expire'] = $expire;
				$this->user_model->update($user_id, $data);
		
		
				// Them vao table tran
				$data = array();
				$data['type'] 		= config('tran_type_renew', 'main');
				$data['status'] 	= config('tran_status_completed', 'main');
				$data['user_id'] 	= $user_id;
				$data['created'] 	= now();
				$data['amount'] 	= $amount;
				$data['value'] 		= $days;
				$data['payment'] 	= 'card';
				$this->tran_model->create($data);
		
				// Lay ma so cua giao dich vua them
				$tran_id = $this->db->insert_id();
		
				// Lay phi cua card
				$card_service_cost = $this->setting_model->get('config-card_service_cost');
				$card_service_cost = floatval($card_service_cost) * 0.01;
				$card_service_cost = $amount * $card_service_cost;
		
				// Luu thong tin card
				$this->payment_card->{$card_payment}->card_save($tran_id, $api_output['data'], $card_service_cost);
		
				$result = array();
				$result['Status']  = 1;
				$result['Message'] = 'Gia hạn thành công thành công,hạn xem tới ngày '.get_date($expire);
				
				$user =  $this->user_model->get_info($user->id);
				$data = array();
				$data['user_id']    = $user->id;
				$data['user_name']  = $user->name;
				$data['user_email'] = $user->email;
				$data['date_exp']   = get_date($user->expire);
				$data['is_expire']  = ($user->expire < now()) ? 1 : 0;
				$data['url_renew']  = site_url('renew');
	
				$result['data']    = $data;
			
				$this->_set_output($result);
			}
		}
		
		// Neu that bai
		else
		{
			$this->_get_error($api_output);
		}
	}
	
	/**
	 * Action: Renew_voucher (Gia han ngay xem phim bằng mã Voucher)
	 * @voucher_key: Mã Voucher
	 * @user_id: Id thành viên gia hạn
	 */
	function Renew_voucher()
	{
		// Tai cac file thanh phan
		$this->load->helper('tran');
		$this->load->model('tran_model');
		$this->load->model('user_model');
		$this->lang->load('site/tran');
		$this->lang->load('site/payment');
		$this->load->model('voucher_model');
	
		$voucher_key  =  isset($this->data['voucher_key']) ? (string)$this->data['voucher_key'] : '';
		$user_id      =  isset($this->data['user_id']) ? (string)$this->data['user_id'] : '';
		
		// Kiem ra du lieu dau vao
		if (!$voucher_key)
		{
			$this->_get_error('Chưa gửi key voucher sang,vui lòng kiểm tra lại');
		}
		if (!$this->_check_key_exist($voucher_key))
		{
			$this->_get_error('Key voucher chưa hợp lệ,vui lòng kiểm tra lại');
		}
		
		//kiem tra thanh vien
		$user_id = intval($user_id);
		$user =  $this->user_model->get_info($user_id);
		if(!$user)
		{
			$this->_get_error('Không tồn tại thành viên này trên hệ thống, vui lòng kiểm tra lại');
		}
		
		$voucher = $this->voucher_model->get_info_rule(array( 'key' => $voucher_key), 'id, value, exp_date, value');
		// Neu thanh cong
		if ($voucher)
		{
			$days_time = convert_day($voucher->value);
		
			// Tinh ngay het han cho user
			$expire = max($user->expire, now());
			$expire = add_time($expire, $days_time);
		
			// Gia han cho user
			$data           = array();
			$data['expire'] = $expire;
			$this->user_model->update($user->id, $data);
		
			//Cap nhat su dung trang thai cho Ma Voucher
			$data           = array();
			$data['status'] = config('voucher_status_used', 'main');
			$this->voucher_model->update($voucher->id, $data);
		
			// Them vao table tran
			$data = array();
			$data['type'] 		= config('tran_type_renew_voucher', 'main');
			$data['status'] 	= config('tran_status_completed', 'main');
			$data['user_id'] 	= $user->id;
			$data['created'] 	= now();
			$data['amount'] 	= 0;
			$data['value'] 		= $voucher->value;;
			$data['payment'] 	= 'voucher';
			$this->tran_model->create($data);
		
			$result = array();
			$result['Status']  = 1;
			$result['Message'] = 'Gia hạn thành công thành công,hạn xem tới ngày '.get_date($expire);
			
			$user =  $this->user_model->get_info($user->id);
			$data = array();
			$data['user_id']    = $user->id;
			$data['user_name']  = $user->name;
			$data['user_email'] = $user->email;
			$data['date_exp']   = get_date($user->expire);
			$data['is_expire']  = ($user->expire < now()) ? 1 : 0;
			$data['url_renew']  = site_url('renew');
			$result['data']     = $data;
			
			$this->_set_output($result);
		}
		

	}
	
	/**
	 * Kiem tra card type
	 */
	private function _renew_check_card_type($value)
	{
		$card_types = config('card_types', 'main');
		if (!in_array($value, $card_types))
		{
			return FALSE;
		}
		return TRUE;
	}
	
	/*
	 * Kiem tra su ton tai cua key
	*/
	private function _check_key_exist($value){
	
		$this->load->model('voucher_model');
		$input                  = array();
		$filter                 = array();
		$filter['key']          = trim($value);
		$filter['exp_date >=']  = TRUE;
		$filter['status']       = config('voucher_status_unused', 'main');
		$voucher = $this->voucher_model->filter_get_list($filter, $input);
	
		if(count($voucher) == 0){
			return FALSE;
		}
		 
		return TRUE;
	}
	
	/**
	 * Lay so ngay gia han tuong ung voi amount
	 */
	private function _renew_get_days($amount, &$time = array())
	{
		// Lay cac muc gia cua card va sap xep tu cao den thap
		$card_amounts = config('card_amounts', 'main');
		rsort($card_amounts, SORT_NUMERIC);
	
		// Lay cac loai card tuong ung voi so tien da thanh toan
		$amounts = array();
		foreach ($card_amounts as $v)
		{
			if ($amount >= $v)
			{
				$amounts[$v] = floor($amount/$v);
				$amount = fmod($amount, $v);
			}
		}
	
		// Lay tong so ngay gia han
		$days = 0;
		$premium_price = $this->setting_model->get('config-premium_price');
		foreach ($premium_price as $a => $d)
		{
			if (isset($amounts[$a]))
			{
				$days += $d*$amounts[$a];
			}
		}
	
		// Quy doi sang ngay thang nam
		$_days = $days;
	
		$time['y'] = floor($_days/360);
		$_days -= $time['y']*360;
	
		$time['m'] = floor($_days/30);
		$_days -= $time['m']*30;
	
		$time['d'] = $_days;
	
		return $days;
	}
    
/*
* ------------------------------------------------------
*  Main Movie
* ------------------------------------------------------
*/
	
	/*
	 * Lay danh sach movie
	 * $page_type: array('actor', 'cat', 'director', 'producer', 'tag', 'country')
	 * $page_type_id: ID loai
	 * $type : 0-> Tất cả (mặc định); 1 -> Phim lẻ; 2-> Phim bộ
	 * $order: update -> Mới cập nhật ( mặc định ); new -> Mới nhất; top -> Xem nhiều nhất; imdb -> Sắp xếp theo điểm IMDB, feature -> Nổi bật, cinema -> phim chiếu rạp  
	 * $page: Trang hiện tại,nếu đang đầu tiên có thể không cần truyền biến này
	 * $pagesize: số phim hiển thị trên 1 trang,nếu không có biến này mặc định là 20
	*/
	function GetListMovie()
	{
		//lay cac du lieu dau vao
		$page_type = isset($this->data['page_type']) ? strval($this->data['page_type']) : false;
		$page_type_id = isset($this->data['page_type_id']) ? intval($this->data['page_type_id']) : 0;
		$type      = isset($this->data['type']) ? intval($this->data['type']) : 0;
		$order     = isset($this->data['order']) ? strval($this->data['order']) : 'update';
		$page      = isset($this->data['page']) ? intval($this->data['page']) : 0;
		$pagesize  = isset($this->data['pagesize']) ? intval($this->data['pagesize']) : $this->config->item('list_limit2', 'main');
		
		// Tao filter
		$filter = array();
		// Tao input
		$input = array();
		
		// Kiem tra page type
		if (in_array($page_type, array('actor', 'cat', 'director', 'producer', 'tag', 'country')))
		{
			// Tai cac file thanh phan
			$this->load->model($page_type.'_model');
			
			// Lay thong tin cua page hien tai
			$page_type_id 	= (!is_numeric($page_type_id)) ? 0 : $page_type_id;
			$page_info 	= $this->{$page_type.'_model'}->get_info($page_type_id);
			
			// Neu khong ton tai
			if ($page_info)
			{
				// Xu ly thong tin cua info
				$page_info = site_create_url($page_type, $page_info);
				$filter[$page_type] = $page_info->id;
			}
		}
		
		//kiem tra kieu
		switch ($type)
		{
			case 1:
				{
					$filter['type'] = $this->config->item('movie_type_movie', 'main');
					break;
				}
			case 2:
				{
					$filter['type'] = $this->config->item('movie_type_series', 'main');
					break;
				}
		}
		
		//sắp xếp
		$input['order'] = array('movie.id', 'desc');
        switch ($order)
        {
        	case 'new':
        	case 'update':
        		{
        			$input['order'] = array('movie.id', 'desc');
        			break;
        		}
        	case 'top':
        		{
        			$input['order'] = array('movie.view', 'desc');
        			break;
        		}	
        	case 'feature':
        		{
        			$filter['feature'] = true;
        			$input['order'] = array('movie.feature', 'desc');
        			break;
        		}
        	case 'cinema':
        		{
        				$filter['cinema'] = true;
        				$input['order'] = array('movie.id', 'desc');
        				break;
        		}		
        	case 'imdb':
        		{
        				$input['order'] = array('movie.imdb', 'desc');
        				break;
        		}	
        }
		
       
		$data = $this->_create_list($filter, $input, $page, $pagesize);
	
		$result = array();
		$result['Status']  = (empty($data)) ? 0 : 1;
		$result['Message'] = 'Danh sách phim page_type:'.$page_type;
		$result['data']    = $data;
	
		$this->_set_output($result);
	}
	
	/**
	 * Lay chi tiet phim
	 */
	function Search()
	{
		// Tai cac file thanh phan
		$this->load->helper('file');
		$this->load->helper('movie');
		$this->load->model('movie_model');
		$this->load->model('country_model');
		
		// Lay tu khoa tim kiem
		$key  = isset($this->data['key']) ? $this->data['key'] : '';
		$key = str_replace('-', ' ', $key);
		
		$page      = isset($this->data['page']) ? intval($this->data['page']) : 0;
		$pagesize  = isset($this->data['pagesize']) ? intval($this->data['pagesize']) : $this->config->item('list_limit2', 'main');
		
		if($page > 0)
		{
			$page = $page - 1;
		}
		$page  = (!$page) ? 0 : $page;
		$page  = $page*$pagesize;
		
		// Tao limit
		$limit = array($page, $pagesize);
		
		// Lay danh sach
		$list = array();
		$search = $this->movie_model->index_search($key, $limit, 'name, director, actor');
		foreach ($search['list'] as $v)
		{
			$row = $this->movie_model->get_info($v->id, $this->movie_model->select);
			if (!$row)
			{
				$search['total'] -= 1;
				$this->movie_model->index_del($v->id);
				continue;
			}
				
			$row = movie_add_info($row);
			$row = site_create_url('movie', $row);
			foreach (array('actor', 'cat', 'director', 'producer', 'tag') as $p)
			{
				$row->$p = $this->movie_model->info_get($p, $row->id);
				foreach ($row->$p as $r)
				{
					$r = site_create_url($p, $r);
				}
			}
			$row->desc = '';
				
			// Get country
			$row->country = $this->country_model->get_info($row->country_id, 'id, name');
			$row->country = site_create_url('country', $row->country);
			
			$list[] = $row;
		}
		
		$result = array();
		$result['Status']  = (empty($list)) ? 0 : 1;
		$result['Message'] = 'Danh sách tìm kiếm';
		$result['data']    = $list;

		$this->_set_output($result);
		
	}
	
	/**
	 * Lay chi tiet phim
	 */
	function GetMovieDetail()
	{
		// Tai cac file thanh phan
		$this->load->helper('movie');
		$this->load->model('movie_model');
		$this->lang->load('site/movie');
		$this->load->helper('file');
		$this->load->helper('site');
		$this->load->helper('common');
		$this->load->model('country_model');
		$this->load->model('movie_link_model');
		$this->load->model('movie_sub_model');
		

		//kiem tra xem phim nay da thich chua
		$userid  = isset($this->data['user_id']) ? intval($this->data['user_id']) : 0;
		$userid = (!is_numeric($userid)) ? 0 : $userid;
		
		//kiem tra IP nếu không phải là ios
		$this->load->library('user_agent');
		$os = strtolower($this->agent->platform());
		if ($os != 'ios')
		{
    		$this->load->model('login_history_model');
    		$where = array('user_id' => $userid/*, 'is_app' => 1*/);
    		$user_login_history = $this->login_history_model->get_info_rule($where);
    		if(!$user_login_history)
    		{
    		    $this->_get_error('Quý khách vui lòng đăng nhập lại để có thể truy cập trang này');
    		}
    		if($user_login_history->ip != $this->input->ip_address())
    		{
    		    $this->_get_error('Lần truy cập gần nhất của tài khoản này là IP khác');
    		}
		}

		$movieid  = isset($this->data['movieid']) ? intval($this->data['movieid']) : 0;
		$movieid = (!is_numeric($movieid)) ? 0 : $movieid;
		$movie = $this->movie_model->get_info($movieid);
		if (!$movie)
		{
			$this->_get_error('Không tồn tại phim có ID: '.$movieid);
		}
		
		// Xu ly thong tin cua movie
		$movie = movie_add_info($movie);
		$movie = site_create_url('movie', $movie);
		
		foreach (array('actor', 'cat', 'director', 'producer', 'tag') as $p)
		{
			$list_info = $this->movie_model->info_get($p, $movie->id);
			foreach ($list_info as $k_info => $row)
			{
			    if(!$row->id)
			    {
			        unset($list_info[$k_info]);
			        continue;
			    }
				$row = site_create_url($p, $row);
			}
			$movie->$p = $list_info;
			
			$movie->desc = replace($movie->desc);
			//$movie->desc = base64_encode($movie->desc);
			
		}
		// Xu ly thong tin cua movie
		$movie->_url_watch_main = site_url('movie/watch/'.$movie->id);

        $this->load->model('server_model');
		$server = $this->server_model->get_info($movie->server_id);
		
		$link_movie = array();
		$movie_link 		= $this->movie_link_model->link_get($movie->id);
		$movie_sub 		    = $this->movie_sub_model->sub_get($movie->id, $server);
		if(is_array($movie_link) &&  !empty($movie_link))
		{
			foreach ($movie_link as $k => $link)
			{
				$link_movie[] = array('eps' => $k, 'link' => $link, 'sub' => isset($movie_sub[$k]) ? $movie_sub[$k] : '');
			}
		}
		$movie->link_movie = $link_movie;
	
		$link_movie_demo    = array();
		$movie_demo_link 	= $this->movie_link_model->demo_get($movie->id);
		$movie_demo_sub 	= $this->movie_sub_model->demo_get($movie->id, $server);
		if(is_array($movie_demo_link) && !empty($movie_demo_link))
		{
			foreach ($movie_demo_link as $k => $link)
			{
				$link_movie_demo[] = array('eps' => $k, 'link' => $link, 'sub' => isset($movie_demo_sub[$k]) ? $movie_demo_sub[$k] : '');
			}
		}
		$movie->link_movie_demo = $link_movie_demo;
		
		
		$movie->country = $this->country_model->get_info($movie->country_id, 'id, name');
		$movie->country = site_create_url('country', $movie->country);
		
		$movie->imdb = floatval($movie->imdb);
		
		$movie->is_favorited = 0;
		$where = array();
		$where['movie_id'] = $movie->id;
		$where['user_id']  = $userid;
		$this->load->Model('movie_favorite_model');
		//kiem tra xem thanh vien da thich phim nay chua
		$id = $this->movie_favorite_model->get_id($where);
		if($id)
		{
			$movie->is_favorited = 1;
		}	
		
		$result = array();
		$result['Status']  = 1;
		$result['Message'] = 'Chi tiết phim ID :'.$movieid;
		$result['data']    = $movie;

		$this->_set_output($result);
	}
	

	/**
	 * Yeu thich phim
	 *  @$movieid: ID Phim 
	 *  @user_id:  Id thành viên
	 * 
	 */
	function favorite()
	{
		$this->load->Model('movie_model');
		$movieid = isset($this->data['movieid']) ? intval($this->data['movieid']) : 0;
		$movieid = (!is_numeric($movieid)) ? 0 : $movieid;
		$movie = $this->movie_model->get_info($movieid);
		if (!$movie)
		{
			$this->_get_error('Không tồn tại phim có ID: '.$movieid);
		}
		
		$this->load->Model('user_model');
		$user_id = isset($this->data['user_id']) ? (string)$this->data['user_id'] : '';
		$user_id = (!is_numeric($user_id)) ? 0 : $user_id;
		$user = $this->user_model->get_info($user_id);
		if (!$user)
		{
			$this->_get_error('Không tồn tại thành viên có ID: '.$user_id);
		}
		
		$where = array();
		$where['movie_id'] = $movieid;
		$where['user_id']  = $user_id;
		$this->load->Model('movie_favorite_model');
	
		//kiem tra xem thanh vien da thich phim nay chua
		$id = $this->movie_favorite_model->get_id($where);
		if($id)
		{
			$this->_get_error('Thành viên đã thích phim này');
		}
	
		//them vao table movie_favorite
		$data = array();
		$data['movie_id'] = $movieid;
		$data['user_id']  = $user_id;
		$data['created']  = now();
		$this->movie_favorite_model->create($data);
	
		// Khai bao du lieu tra ve
		$result = array();
		$result['Status']  = 1;
		$result['Message'] = 'Yêu thích phim thành công';
			
		$result['data']    = 'NULL';
		$this->_set_output($result);
	}
	
	/**
	 * Xóa Yeu thich phim
	 *  @$movieid: ID Phim 
	 *  @user_id:  Id thành viên
	 */
	function favorite_del()
	{
		$this->load->Model('movie_model');
		$movieid = isset($this->data['movieid']) ? intval($this->data['movieid']) : 0;
		$movieid = (!is_numeric($movieid)) ? 0 : $movieid;
		$movie = $this->movie_model->get_info($movieid);
		if (!$movie)
		{
			$this->_get_error('Không tồn tại phim có ID: '.$movieid);
		}
		
		$this->load->Model('user_model');
		$user_id = isset($this->data['user_id']) ? (string)$this->data['user_id'] : '';
		$user_id = (!is_numeric($user_id)) ? 0 : $user_id;
		$user = $this->user_model->get_info($user_id);
		if (!$user)
		{
			$this->_get_error('Không tồn tại thành viên có ID: '.$user_id);
		}
		
		$where = array();
		$where['movie_id'] = $movieid;
		$where['user_id']  = $user_id;
		$this->load->Model('movie_favorite_model');
	
		//kiem tra xem thanh vien da thich phim nay chua
		$id = $this->movie_favorite_model->get_id($where);
		if(!$id)
		{
			$this->_get_error('Thành viên chưa thích phim này');
		}
		$this->movie_favorite_model->del($id);
		
		// Khai bao du lieu tra ve
		$result = array();
		$result['Status']  = 1;
		$result['Message'] = 'Xóa yêu thích phim thành công';
			
		$result['data']    = 'NULL';
		$this->_set_output($result);
	
	}
	
	
	/**
	 * Phim da yeu thich cua thanh vien
	 * @user_id:  Id thành viên
	 */
	function favorited()
	{
	   
		$this->load->Model('user_model');
		$user_id = isset($this->data['user_id']) ? (string)$this->data['user_id'] : '';
		$user_id = (!is_numeric($user_id)) ? 0 : $user_id;
		$user = $this->user_model->get_info($user_id);
		if (!$user)
		{
			$this->_get_error('Không tồn tại thành viên có ID: '.$user_id);
		}

        //tai cac file thanh phan
		$this->load->helper('file');
		$this->load->helper('movie');
		$this->load->model('movie_model');
		$this->load->model('country_model');
		
		$where = array();
		$where['select']  = 'movie.*';
		$where['order']   = array('movie_favorite.created','DESC');
		$where['where']   = array('movie_favorite.user_id' => $user->id);
	
		//kiem tra xem thanh vien da thich phim nay chua
		$list = $this->movie_model->get_list($where);
		foreach ($list as $row)
		{
			$row = movie_add_info($row);
			$row = site_create_url('movie', $row);
			foreach (array('actor', 'cat', 'director', 'producer', 'tag') as $p)
			{
				$row->$p = $this->movie_model->info_get($p, $row->id);
				foreach ($row->$p as $r)
				{
					$r = site_create_url($p, $r);
				}
			}
			$row->desc = '';
				
			// Get country
			$row->country = $this->country_model->get_info($row->country_id, 'id, name');
			$row->country = site_create_url('country', $row->country);
		}
		
		//ket qua tra ve
		$result = array();
		$result['Status']  = 1;
		$result['Message'] = 'Danh sách phim yêu thích';
		$result['data']    = $list;
	
		$this->_set_output($result);
	}
	
	
	/**
	 * Tao danh sach hien thi
	 */
	private function _create_list($filter, $input, $page, $page_size)
	{
		// Gan filter
		$filter['hide'] = FALSE;
	
		// Tai cac file thanh phan
		$this->load->helper('file');
		$this->load->helper('movie');
		$this->load->model('movie_model');
		$this->load->model('country_model');
		
		// Lay tong so
		$total = $this->movie_model->filter_get_total($filter);
		if($page > 0)
		{
			$page = $page - 1;
		}
		$page  = (!$page) ? 0 : $page;
		$limit = $page*$page_size;
		//$limit = min($limit, get_limit_page_last($total, $page_size));
		//$limit = max(0, $limit);
	
		// Lay danh sach
		$input['limit'] = array($limit, $page_size);
		$list = $this->movie_model->filter_get_list($filter, $input);
		foreach ($list as $row)
		{
			$row = movie_add_info($row);
			$row = site_create_url('movie', $row);
			foreach (array('actor', 'cat', 'director', 'producer', 'tag') as $p)
			{
				$list_info = $this->movie_model->info_get($p, $row->id);
				foreach ($list_info as $k_info => $r)
				{
				    if(!$r->id)
				    {
				        unset($list_info[$k_info]);
				        continue;
				    }
					$r = site_create_url($p, $r);
				}
				$row->$p = $list_info;
			}
			$row->desc = '';
				
			// Get country
			$row->country = $this->country_model->get_info($row->country_id, 'id, name');
			$row->country = site_create_url('country', $row->country);
		}
		
		return $list;
	}

	
	/*
	 *Lay danh sach the loai
	*/
	private function _get_cat()
	{
		$this->load->model('cat_model');
		$list = $this->cat_model->get();
		$cats = array();
		foreach ($list as $row)
		{
			$row = site_create_url('cat', $row);
			$cats[] = array(
				     'Id'   => $row->id,
					 'page_type' => 'cat',
				     'Name' => $row->name,
				     'Link' => $row->_url_view,
				     'Sub'  => array()
		          );
		}
		return $cats;
	}
	/*
	 *Lay danh sach quoc gia
	*/
	private function _get_country()
	{
	    // Tai cac file thanh phan
		$this->load->driver('cache');
		
		// Lay du lieu trong cache
		$list = $this->cache->file->get('m_country');
		
		// Neu khong ton tai thi lay trong data va cap nhat lai cache
		if ($list === FALSE)
		{
			$this->load->model('country_model');
			
			$list = $this->country_model->get_list();
			$this->cache->file->save('m_country', $list, config('cookie_expire', 'main'));
		}
			
		// Xu ly danh sach the loai
		$countrys = array();
		foreach ($list as $row)
		{
			$row = site_create_url('country', $row);
			$countrys[] = array(
					'Id'   => $row->id,
					'page_type' => 'country',
					'Name' => $row->name,
					'Link' => $row->_url_view,
					'Sub'  => array()
			);
		}
		return $countrys;
	}
/*
* ------------------------------------------------------
*  Main orther
* ------------------------------------------------------
*/
	/*
	 * Lay danh sach menu
	*/
	function GetListSlide()
	{
		$result = array();
		$result['Status']  = 1;
		$result['Message'] = 'Danh sách slide';
	
		$this->load->helper('slide');
		$this->load->Model('slide_model');
		
		$input = array();
		$input['order'] = array('sort_order','ASC');
		$list = $this->slide_model->get_list($input);
		foreach ($list as $row)
		{
			$row->image  = slide_get_image($row->image_id);
			$row = slide_add_info($row);
		}
	
		$result['data']    = $list;
	
		$this->_set_output($result);
	}
	
	/*
	 * Lay thong bao
	*/
	function GetNotice()
	{
		$result = array();
		$result['Status']  = 1;
		$result['Message'] = 'Thông báo';
		
		// Neu notice bi tat
		$notice = $this->setting_model->get('config-notice');
		if (!$notice)
		{
			$notice = '';
		}else{
			// Lay noi dung thong bao
			$notice = $this->setting_model->get('config-notice_content');
		}
		
		$result['data']    = $notice;
		$this->_set_output($result);
	}
	
	/*
	 * Lay tag
	*/
	function GetTag()
	{
		$result = array();
		$result['Status']  = 1;
		$result['Message'] = 'Lay danh sach tag';
	
	    // Tai file thanh phan
		$this->load->model('menu_model');
		// Xu ly location
		$location = 'tag';
		$locations = config('menu_locations', 'main');
		$location = (!in_array($location, $locations)) ? '' : $location;
		
		// Lay danh sach menu
		$menu = array();
		if ($location)
		{
			$location_id = config('menu_location_'.$location, 'main');
			$menu = $this->menu_model->get($location_id);
		}
		
		$result['data']    = $menu;
		$this->_set_output($result);
	}
	
	
	/*
	 * Lay danh sach menu
	*/
	function GetListmenu()
	{
		$result = array();
		$result['Status']  = 1;
		$result['Message'] = 'Danh sách menu';
	
		$data = array();
	
		// Lay danh sach the loai
		$cats = $this->_get_cat();
		$data[] = array(
				'Id'   => 1,
				'Name' => 'Thể loại',
				'Link' => '',
				'Subs'  => $cats
		);
	
		// Lay danh sach quoc gia
		$countrys = $this->_get_country();
		$data[] = array(
				'Id'   => 2,
				'Name' => 'Quốc gia',
				'Link' => '',
				'Subs'  => $countrys
		);
	
		//phim le
		$data[] = array(
				'Id'   => 3,
				'Name' => 'Phim lẻ',
				'Link' => site_url('movie/movies'),
				'Subs' => array()
		);
	
		//phim bo
		$data[] = array(
				'Id'   => 4,
				'Name' => 'Phim bộ',
				'Link' => site_url('movie/series'),
				'Subs' => array()
		);
	
		$result['data']    = $data;
	
		$this->_set_output($result);
	}
	
	/*
	 * Tra ket qua loi
	*/
	function version()
	{
		$os = isset($this->data['os']) ? (string)$this->data['os'] : '';
		if(!in_array($os, array('android', 'ios')))
		{
			$this->_get_error('Không tồn hệ điều hành này: '.$os);
		}
		
		$this->load->model('setting_model');
		$version = array();
		$version['version'] = $this->setting_model->get('config-'.$os.'_version');
		$version['link']    = $this->setting_model->get('config-'.$os.'_link');
		
		//ket qua tra ve
		$result = array();
		$result['Status']  = 1;
		$result['Message'] = 'Phiên bản '.$os;
		$result['data']    = $version;
	
		$this->_set_output($result);
	}
	
	/*
	* Tra ket qua loi
	*/
	private function _get_error($message = '')
	{
		$result = array();
		$result['Status']  = -1;
		$result['Message'] = $message;
		$result['data']    = NULL;
		
		$this->_set_output($result);
	}
	
	/*
	* Tra ket ket qua
	*/
	private function _set_output($result)
	{
		$output = json_encode($result);
		set_output('json', $output);
	}
	

	function writeFileLog($file_name, $data)
	{
		$fp = fopen($file_name,'a');
		if ($fp) {
	
			$line = date("H:i:s, d/m/Y:  ",time()).$data. " \n";
			fwrite($fp,$line);
			fclose($fp);
		}
	}
}