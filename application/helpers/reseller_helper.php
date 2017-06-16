<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	/**
	 * Lay thong tin cua thanh vien
	 */
	function reseller_get_info($reseller_id, $field = '')
	{		
		$CI =& get_instance();
		$CI->load->model('reseller_model');
		
		$info = $CI->reseller_model->get_info($reseller_id, $field);
		if ( ! $info)
		{
			return FALSE;
		}
		
		$info = reseller_add_info($info);
		
		return $info;
	}
	
	/**
	 * Them thong tin ngoai vao thong tin cua reseller
	 */
	function reseller_add_info($reseller)
	{
		if (!$reseller)
		{
			return FALSE;
		}

		$CI =& get_instance();
		
		if (isset($reseller->created))
		{
			$reseller->_created = get_date($reseller->created,'full');
		}
		if (isset($reseller->last_login))
		{
			$reseller->_last_login ='Never';
			if($reseller->last_login)
			$reseller->_last_login = get_date($reseller->last_login,'full');
		}
		if (isset($reseller->verify))
		{
			$vs = config('reseller_verifies', 'main');
			$reseller->_verify = (isset($vs[$reseller->verify])) ? $vs[$reseller->verify] : '';
		}
		if (isset($reseller->blocked))
		{
			$vs = config('verify', 'main');
			$reseller->_blocked = (isset($vs[$reseller->blocked])) ? $vs[$reseller->blocked] : '';
		}
		//if (isset($reseller->avatar))
		//{
			$CI->load->helper('file');
			$avatar_name =$reseller->avatar;// (isset($reseller->avatar_name)) ? $reseller->avatar_name : '';
			$reseller->avatar = file_get_image_from_name($avatar_name, public_url('img/reseller_no_image.png'));
		//}
		if (isset($reseller->reseller_group_id))
		{
			$reseller->reseller_group_name = model('reseller_group')->get_info($reseller->reseller_group_id,'name')->name;
		}
		if (isset($reseller->desc) && !empty($reseller->desc))
		{
			$reseller->desc = handle_content($reseller->desc,'output');
		}

		return $reseller;
	}
		
	/**
	 * Lay avatar cua thanh vien
	 */
	function reseller_get_avatar($avatar_id)
	{
		$CI =& get_instance();
		$CI->load->helper('file');
		
		$file = file_get_info($avatar_id, 'file_name');
		$file_name = (!isset($file->file_name)) ? '' : $file->file_name;
		
		return file_get_image_from_name($file->file_name, public_url('img/reseller_no_image.png'));
	}
	
	/**
	 * Kiem tra co the thuc hien 1 hanh dong voi reseller
	 */
	function reseller_can_do($reseller, $action)
	{
		$CI =& get_instance();
		
		switch ($action)
		{
			case 'edit':
			case 'admin_login':
			{
				return TRUE;
			}
			case 'block':
			{
				return ($reseller->blocked == config('verify_no', 'main'));
			}
			case 'unblock':
			{
				return ($reseller->blocked == config('verify_yes', 'main'));
			}
			case 'verify':
			{
				return ($reseller->verify == config('reseller_verify_no', 'main'));
			}
			case 'verify_view':
			case 'verify_cancel':
			{
				return ($reseller->verify != config('reseller_verify_no', 'main'));
			}
			case 'verify_edit':
			case 'verify_accept':
			{
				return ($reseller->verify == config('reseller_verify_wait', 'main'));
			}
			case 'del':
			{
				return ($reseller->blocked == config('verify_yes', 'main'));
			}
		}
		
		return FALSE;
	}
	
	
/*
 * ------------------------------------------------------
 *  reseller login handle
 * ------------------------------------------------------
 */
	/**
	 * Kiem tra thong tin dang nhap
	 * @param string $email			Email
	 * @param string $password		Mat khau (Da duoc ma hoa)
	 * @return
	 *	Error:
	 * 		$r = array();
	 *		$r['status'] = FALSE;
	 *		$r['result']['error'] = 'error';
	 *			Cac gia tri cua error:
	 * 			'input' 				= Du lieu dau vao khong hop le
	 * 			'ip_blocked' 			= IP bi block
	 * 			'email' 				= Sai email
	 * 			'password' 				= Sai password
	 * 			'ip_blocked_login_fail' = IP bi block do dang nhap sai qua so lan quy dinh
	 * 			'blocked' 				= Tai khoan bi block
	 *	Completed:
	 * 		$r = array();
	 *		$r['status'] = TRUE;
	 *		$r['result']['reseller'] = (object)$reseller (Thong tin reseller tuong ung);
	 */
	function reseller_login($email, $password)
	{
		// Tai cac file thanh phan
		$CI =& get_instance();
		$CI->load->helper('email');
		$CI->load->model('reseller_model');
		$CI->load->model('ip_model');
		$CI->load->model('ip_block_model');
		
		// Xu ly input
		$email 		= (string)$email;
		$password 	= (string)$password;
		
		// Neu input khong hop le
		if ( ! $email || ! $password || ! valid_email($email))
		{
			$r = array();
			$r['status'] = FALSE;
			$r['result']['error'] = 'input';
			
			return $r;
		}
		
		// Neu IP bi block
		$ip = $CI->input->ip_address();
		if ($CI->ip_block_model->check($ip))
		{
			$r = array();
			$r['status'] = FALSE;
			$r['result']['error'] = 'ip_blocked';
			
			return $r;
		}
		
		// Neu khong ton tai reseller tuong ung
		$where = array();
		$where['email'] = $email;
		$reseller = $CI->reseller_model->get_info_rule($where);
		if ( ! $reseller)
		{
			$r = array();
			$r['status'] = FALSE;
			$r['result']['error'] = 'email';
			
			return $r;
		}
		
		// Neu sai mat khau
		if ($reseller->password != $password)
		{
			$r = array();
			$r['status'] = FALSE;
			$r['result']['error'] = 'password';
			// Kiem tra so lan dang nhap sai
			if ( ! reseller_login_check_fail_count())
			{
				$r['result']['error'] = 'ip_blocked_login_fail';
			}
			
			return $r;
		}
		
		// Neu reseller bi khoa
		if ($reseller->blocked == config('verify_yes', 'main'))
		{
			$r = array();
			$r['status'] = FALSE;
			$r['result']['error'] = 'blocked';
			
			return $r;
		}
		
		// Reset so lan dang nhap sai cua IP
		$login_fail_count = $CI->ip_model->action_count_get('reseller_login_fail');
		if ($login_fail_count)
		{
			$CI->ip_model->action_count_set('reseller_login_fail', 0);
		}
		
		// Dang nhap thanh cong
		$r = array();
		$r['status'] = TRUE;
		$r['result']['reseller'] = (object)(array)$reseller;
		
		return $r;
	}
	
	/**
	 * Kiem tra so lan dang nhap sai
	 */
	function reseller_login_check_fail_count()
	{
		// Tai cac file thanh phan
		$CI =& get_instance();
		$CI->load->model('ip_model');
		$CI->load->model('ip_block_model');
		
		// Neu khong can kiem tra
		$login_fail_count_max = mod('reseller')->setting('login_fail_count_max');

		if (  $login_fail_count_max == 0)
		{
			return TRUE;
		}

		// Cap nhat so lan dang nhap sai cua IP
		$login_fail_count = $CI->ip_model->action_count_get('reseller_login_fail');
		$login_fail_count += 1;
		$CI->ip_model->action_count_set('reseller_login_fail', $login_fail_count);
		
		// Neu so lan dang nhap sai lon hon quy dinh
		if ($login_fail_count >= $login_fail_count_max)
		{
			// Block ip
			$ip = $CI->input->ip_address();
			$CI->ip_block_model->set($ip, mod('reseller')->setting('login_fail_block_timeout'));
			
			return FALSE;
		}
		
		return TRUE;
	}
	
	/**
	 * Gan trang thai dang nhap
	 */
	function reseller_login_set($reseller_id)
	{
		// Tai cac file thanh phan
		$CI =& get_instance();
		$CI->load->model('reseller_model');
		
		// Luu IP
		$ip = $CI->input->ip_address();
		$data =array();
		$data['ip']=$ip;
		$data['last_ip']=$ip;
		$data['last_login']=now();
		$CI->reseller_model->update($reseller_id,$data);
		

		// Tao token bao mat phien lam viec
		$reseller_token= md5($reseller_id.$ip.config('encryption_key'));
		// Set session
		$CI->session->set_resellerdata('__reseller_id', $reseller_id);
		$CI->session->set_resellerdata('__reseller_token', $reseller_token);


		// Luu log
		$log_info = array();
		//$log_info ['detail']=t('html')->a(admin_url('reseller').'?id='.$reseller->id,$reseller->name) .' '.lang('login');
		$log_info ['detail']=lang('login');
		mod('log')->log('reseller', $reseller_id, 'login',$log_info,true);

	}
	
	/**
	 * Luu cookie ghi nho dang nhap
	 * @param string $email			Email
	 * @param string $password		Mat khau (Da duoc ma hoa)
	 */
	function reseller_login_set_cookie($email, $password, $expire = null)
	{
		$CI =& get_instance();
		
		$cookie = array();
		$cookie['email'] 	= $email;
		$cookie['password'] = $password;
		//$cookie['ip'] 		= $CI->input->ip_address();
		
		$cookie = json_encode($cookie);
		$cookie = security_encrypt($cookie, 'encode');
		
		$expire = is_null($expire) ? config('cookie_expire_login', 'main') : $expire;
		
		set_cookie('reseller', $cookie, $expire);
	}
	
	/**
	 * Kiem tra thong tin dang nhap trong cookie
	 */
	function reseller_login_check_cookie()
	{
		$CI =& get_instance();
		
		// Lay cookie
		$cookie = get_cookie('reseller', TRUE);
		$cookie = security_encrypt($cookie, 'decode');
		$cookie = @json_decode($cookie);
		if (
			! isset($cookie->email)
			|| ! isset($cookie->password)
			//|| ! isset($cookie->ip)
		)
		{
			return FALSE;
		}
		
		// Kiem tra IP
		/* if ($cookie->ip != $CI->input->ip_address())
		{
			// Reset cookie
			reseller_logout();
			
			return FALSE;
		} */
		
		// Kiem tra email, password
		$login = reseller_login($cookie->email, $cookie->password);
		if ( ! $login['status'])
		{
			// Reset cookie
			reseller_logout();
			
			return FALSE;
		}
		
		// Gan trang thai dang nhap
		reseller_login_set($login['result']['reseller']->id);
		
		return TRUE;
	}
	
	/**
	 * Kiem tra reseller da dang nhap hay chua
	 */
	function reseller_is_login(&$reseller_id_return = 0)
	{
		$CI =& get_instance();
		$ip = $CI->input->ip_address();

		// kiem tra id
		$reseller_id = $CI->session->resellerdata('__reseller_id');
		$reseller_id = ( ! is_numeric($reseller_id)) ? 0 : $reseller_id;
		if($reseller_id <= 0)
			return FALSE;
		// kiem tra token
		$reseller_token = $CI->session->resellerdata('__reseller_token');
		$reseller_token_check= md5($reseller_id.$ip.config('encryption_key'));
		if($reseller_token != $reseller_token_check)
			return false;
		// tra ve ket qua
		$reseller_id_return = $reseller_id ;
		return  TRUE;


		$CI =& get_instance();
		
		$reseller_id = $CI->session->resellerdata('reseller_id');
		$reseller_id = ( ! is_numeric($reseller_id)) ? 0 : $reseller_id;
		
		return ($reseller_id > 0) ? TRUE : FALSE;
	}
	
	/**
	 * Lay thong tin cua reseller hien tai
	 * 
	 * @return false|object
	 */
	function reseller_get_account_info()
	{
		// Neu chua login
		$reseller_id = 0;
		if ( ! reseller_is_login($reseller_id))
		{
			return false;
		}
		
		// Bien tinh luu thong tin reseller hien tai
		static $reseller;
		
		// Neu thong tin chua duoc get hoac reseller_id khong phu hop
		if ( ! isset($reseller->id) || $reseller->id != $reseller_id)
		{
			// Lay thong tin reseller
			$reseller = model('reseller')->get_info($reseller_id);
			
			// Neu khong ton tai reseller
			if ( ! $reseller)
			{
				// Logout tai khoan hien tai
				reseller_logout();
				
				return false;
			}
			
//			$reseller->balance = model('reseller')->balance_get($reseller->id);
//			$reseller->_balance = currency_format_amount_default($reseller->balance);
//			$reseller->permissions = model('reseller_group')->get_permissions($reseller->reseller_group_id);
		}
		
		return $reseller ? (object)(array)$reseller : false;
	}
	
	/**
	 * reseller dang xuat
	 */
	function reseller_logout()
	{
		$CI =& get_instance();
		
		// Xoa session
		if ($CI->session->resellerdata('__reseller_id') !== FALSE)
		{
			$CI->session->unset_resellerdata('__reseller_id');
		}
		
		// Xoa cookie
		if (get_cookie('reseller') !== FALSE)
		{
			delete_cookie('reseller');
		}
	}


/*
 * ------------------------------------------------------
 *  reseller permission handle
 * ------------------------------------------------------
 */
/**
 * Kiem tra quyen truy cap cua reseller
 * @param string $c		Ten controller
 * @param string $uri	Uri truy cap cua controller
 */
function reseller_permission($c, $uri)
{
	return true;
	//echo '<br>-check per : c='.$c.' - uri='.$uri;
	$reseller =reseller_get_account_info();

	$CI =& get_instance();

	// Neu la controller mac dinh
	$c_main = array('home', 'login', 'file', 'md');
	if (in_array($c, $c_main))
	{
		return TRUE;
	}

	// Lay config
	$config = reseller_permission_list();
	// Lay permissions cua reseller
	$permissions = $reseller->permissions;
	// Neu duoc phep truy cap controller nay
	if (isset($permissions[$c]) && is_array($permissions[$c]))
	{
		// Duyet qua cac permission
		foreach ($permissions[$c] as $p)
		{
			// Lay uri cua permission
			$p_uri = (isset($config[$c][$p]['uri'])) ? $config[$c][$p]['uri'] : FALSE;
			if ( ! is_array($p_uri))
			{
				continue;
			}

			// Kiem tra uri hien tai
			$uri = trim($uri, '/').'/';
			foreach ($p_uri as $_uri)
			{
				$_uri = trim($_uri, '/').'/';
				if (preg_match('#^'.preg_quote($_uri).'#i', $uri))
				{
					return TRUE;
				}
			}
		}
	}

	return FALSE;
}

/**
 * Kiem tra reseller co quyen truy cap url hay khong
 * @param string $url	URL can kiem tra
 */
function reseller_permission_url($url)
{

	$CI =& get_instance();

	// Lay uri
	$uri = url_get_uri($url);
	$uri = mod('seo_url')->get_route_base($uri);
	$uri = explode('/', $uri);
	//pr($CI->router->default_controller);
	// Lay controller
	//$c = (isset($uri[1]) && $uri[1] != '') ? $uri[1] : $CI->router->routes['default_controller'];
	$c = (isset($uri[0]) && $uri[0] != '') ? $uri[0] : $CI->router->default_controller;

	// Lay uri cua controller
	unset($uri[0]);


	$u = ( ! count($uri)) ? array('index') : $uri;
	$u = implode('/', $u);
	return reseller_permission($c, $u);
}

/**
 * Lay danh sach permission trong config
 */
function reseller_permission_list()
{
	static $_list = NULL;

	if ($_list === NULL)
	{
		// Load config
		include APPPATH.'config/permissions'.EXT;
		$permissions = (isset($permissions)) ? $permissions : array();

		// Lay danh sach permission
		$_list = array();
		foreach ($permissions as $c => $ps)
		{
			foreach ($ps as $p => $p_i)
			{
				$_list[$c][$p]['name']	= (isset($p_i['name'])) ? $p_i['name'] : '';
				$_list[$c][$p]['uri']	= (isset($p_i['uri'])) ? $p_i['uri'] : $p_i;
			}
		}

		// Lay permission cua cac module da cai dat
		$CI =& get_instance();
		$CI->load->model('module_model');

		$input = array();
		$input['select'] = 'key';
		$modules = $CI->module_model->get_list($input);
		foreach ($modules as $module)
		{
			$c 	= 'md-'.$module->key;
			$ps = $CI->module->{$module->key}->config->item('permissions');
			foreach ($ps as $p => $p_i)
			{
				$_list[$c][$p]['name']	= (isset($p_i['name'])) ? $p_i['name'] : '';
				$_list[$c][$p]['uri']	= (isset($p_i['uri'])) ? $p_i['uri'] : $p_i;
			}
		}
	}

	return $_list;
}

	