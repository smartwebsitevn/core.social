<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	/**
	 * Chuyen trang
	 */
	function redirect_admin($uri = '', $method = 'location', $http_response_code = 302)
	{
		if ( ! preg_match('#^https?://#i', $uri))
		{
			$uri = admin_url($uri);
		}
		
		redirect($uri);
	}
	
	/**
	 * Tao cac lien ket
	 */
	function admin_url($uri = '', array $opt = array())
	{
		$admin_folder = config('admin_folder', 'main');
		$uri = $admin_folder.'/'.$uri ;
		
		return site_url($uri, $opt);
	}

	/**
	 * Tao cac lien ket trong admin
	 */
	function admin_create_url($mod, $row = '')
	{
		// Kiem tra $row
		$is_mod_page = preg_match('#^(.+)_page$#is', $mod);
		if ( ! $is_mod_page && ! $row)
		{
			return FALSE;
		}
		
		// Tao url
		switch ($mod)
		{
			// Admin
			case 'admin':
			{
				$row->_url_view = admin_url('admin/edit/'.$row->id);
				break;
			}
			
			// User
			case 'user':
			{
				$row->_url_view = admin_url('user/edit/'.$row->id);
				break;
			}
			
			// Default
			default:
			{
				// Tao url cho cac page cua mod
				$match = '';
				if (preg_match('#^(.+)_page$#is', $mod, $match))
				{
					$mod = $match[1];
					$url = ($row == '') ? admin_url($mod) : admin_url($mod.'/'.$row);
					return $url;
				}
			}
		}
		
		return $row;
	}

	/**
	 * Tao cac link option cho danh sach
	 */
	function admin_url_create_option($list, $uri, $key, $options,$params=null)
	{
		$is_array = TRUE;
		if ( ! is_array($list))
		{
			$is_array = FALSE;
			$list = array($list);
		}
		$uri = strtolower($uri);
		$uri = trim($uri, '/');
		
		foreach ($list as $row)
		{
			foreach ($options as $option)
			{
				$row->{'_url_'.$option} = admin_url($uri.'/'.$option.'/'.$row->{$key}.$params);
			}
		}
		
		return ($is_array) ? $list : $list[0];
	}

	/**
	 * Lay thong tin cua admin
	 */
	function admin_get_info($id, $field = '')
	{		
		$CI =& get_instance();
		$CI->load->model('admin_model');
		
		$info = $CI->admin_model->get_info($id, $field);
		if ( ! $info)
		{
			return FALSE;
		}
		
		$info = admin_add_info($info);
		
		return $info;
	}
	
	/**
	 * Them thong tin ngoai vao thong tin cua admin
	 */
	function admin_add_info($row)
	{
		if ( ! $row)
		{
			return FALSE;
		}
		
		$CI =& get_instance();
		$group =model('admin_group')->get_info($row->admin_group_id);
		$row->_avata_path = $row->_avata_thumb_path = '';
		$row->_avata = $row->_avata_thumb = base_url('public/img/admin_no_image.png');
		$row->level =$group->level;
		$row->group_id =$group->id;
		$row->is_root =false;
		if($row->id == 1)
			$row->is_root =true;
		return $row;
	}
	
	
/*
 * ------------------------------------------------------
 *  Admin login handle
 * ------------------------------------------------------
 */
	/**
	 * Kiem tra thong tin dang nhap
	 * @param string $username		Ten dang nhap
	 * @param string $password		Mat khau (Da duoc ma hoa)
	 * @param array  $matrix		Gia tri the xac thuc
	 * @return
	 *	Error:
	 * 		$r = array();
	 *		$r['status'] = FALSE;
	 *		$r['result']['error'] = 'error';
	 *			Cac gia tri cua error:
	 * 			'input' 				= Du lieu dau vao khong hop le
	 * 			'ip_blocked' 			= IP bi block
	 * 			'username' 				= Sai username
	 * 			'password' 				= Sai password
	 * 			'matrix' 				= The xac thuc khong dung
	 * 			'ip_blocked_login_fail' = IP bi block do dang nhap sai qua so lan quy dinh
	 *	Completed:
	 * 		$r = array();
	 *		$r['status'] = TRUE;
	 *		$r['result']['admin'] = (object)$admin (Thong tin admin tuong ung);
	 */
	function admin_login($username, $password, array $matrix = array())
	{
		// Tai cac file thanh phan
		$CI =& get_instance();
		$CI->load->model('admin_model');
		$CI->load->model('ip_model');
		$CI->load->model('ip_block_model');
		
		// Xu ly input
		$username = (string)$username;
		$password = (string)$password;
		
		// Neu input khong hop le
		if ( ! $username || ! $password)
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
		
		// Neu khong ton tai admin tuong ung
		$where = array();
		$where['username'] = $username;
		$admin = $CI->admin_model->get_info_rule($where);
		if ( ! $admin)
		{
			$r = array();
			$r['status'] = FALSE;
			$r['result']['error'] = 'username';
			
			return $r;
		}
		
		// Neu sai mat khau
		if ($admin->password != $password)
		{
			$r = array();
			$r['status'] = FALSE;
			$r['result']['error'] = 'password';

			// Kiem tra so lan dang nhap sai
			if ( ! admin_login_check_fail_count())
			{
				$r['result']['error'] = 'ip_blocked_login_fail';
			}

			return $r;
		}

		// Neu user bi khoa
		if ($admin->blocked == config('verify_yes', 'main'))
		{
			$r = array();
			$r['status'] = FALSE;
			$r['result']['error'] = 'blocked';

			return $r;
		}
		// Kiem tra matrix
		if (count($matrix))
		{
			// Lay matrix cua admin
			$admin_matrix = $CI->admin_model->matrix_get($admin->id);
			
			// Kiem tra matrix
			foreach ($matrix as $position => $code)
			{
				$code = (string)$code;
				$r = left($position, 1);
				$c = right($position, 1);
				
				if (
					! strlen($code) || 
					! isset($admin_matrix[$r][$c]) || 
					$admin_matrix[$r][$c] != $code
				)
				{
					$r = array();
					$r['status'] = FALSE;
					$r['result']['error'] = 'matrix';
					
					// Kiem tra so lan dang nhap sai
					if ( ! admin_login_check_fail_count())
					{
						$r['result']['error'] = 'ip_blocked_login_fail';
					}
					
					return $r;
				}
			}
		}
		
		// Reset so lan dang nhap sai cua IP
		$login_fail_count = $CI->ip_model->action_count_get('admin_login_fail');
		if ($login_fail_count)
		{
			$CI->ip_model->action_count_set('admin_login_fail', 0);
		}
		
		// Dang nhap thanh cong
		$r = array();
		$r['status'] = TRUE;
		$r['result']['admin'] = (object)(array)$admin;
		
		return $r;
	}
	
	/**
	 * Kiem tra so lan dang nhap sai
	 */
	function admin_login_check_fail_count()
	{
		// Tai cac file thanh phan
		$CI =& get_instance();
		$CI->load->model('ip_model');
		$CI->load->model('ip_block_model');
		
		// Neu khong can kiem tra
		$login_fail_count_max = config('login_fail_count_max', 'main');
		if ( ! $login_fail_count_max)
		{
			return TRUE;
		}
		
		// Cap nhat so lan dang nhap sai cua IP
		$login_fail_count = $CI->ip_model->action_count_get('admin_login_fail');
		$login_fail_count += 1;
		$CI->ip_model->action_count_set('admin_login_fail', $login_fail_count);
		
		// Neu so lan dang nhap sai lon hon quy dinh
		if ($login_fail_count >= $login_fail_count_max)
		{
			// Block ip
			$ip = $CI->input->ip_address();
			$CI->ip_block_model->set($ip, config('login_fail_block_timeout', 'main'));
			
			return FALSE;
		}
		
		return TRUE;
	}
	
	/**
	 * Gan trang thai dang nhap
	 */
	function admin_login_set($admin_id)
	{
		$CI =& get_instance();
		
		// Luu IP
		$ip = $CI->input->ip_address();
		$CI->admin_model->update_field($admin_id, 'ip', $ip);
		

		// Tao token bao mat phien lam viec
		$admin_token= md5($admin_id.$ip.config('encryption_key'));
		// Set session
		$CI->session->set_userdata('__admin_id', $admin_id);
		$CI->session->set_userdata('__admin_token', $admin_token);

		// Luu log
		$log_info = array();
		//$log_info ['detail']=t('html')->a(admin_url('admin').'?id='.$admin->id,$admin->name) .' '.lang('login');
		$log_info ['detail']=lang('login');
		mod('log')->log('admin',$admin_id,'login',$log_info,true );

	}
	
	/**
	 * Luu cookie ghi nho dang nhap
	 * @param string $username		Ten dang nhap
	 * @param string $password		Mat khau (Da duoc ma hoa)
	 */
	function admin_login_set_cookie($username, $password)
	{
		$CI =& get_instance();
		
		$cookie = array();
		$cookie['username'] = $username;
		$cookie['password'] = $password;
		$cookie['ip'] 		= $CI->input->ip_address();
		
		$cookie = json_encode($cookie);
		$cookie = security_encrypt($cookie, 'encode');
		
		$expire = config('cookie_expire_login', 'main');
		
		set_cookie('admin', $cookie, $expire);
	}
	
	/**
	 * Kiem tra thong tin dang nhap trong cookie
	 */
	function admin_login_check_cookie()
	{
		$CI =& get_instance();
		
		// Lay cookie
		$cookie = get_cookie('admin', TRUE);
		$cookie = security_encrypt($cookie, 'decode');
		$cookie = @json_decode($cookie);
		if (
			! isset($cookie->username) ||
			! isset($cookie->password) ||
			! isset($cookie->ip)
		)
		{
			return FALSE;
		}
		
		// Kiem tra IP
		if ($cookie->ip != $CI->input->ip_address())
		{
			// Reset cookie
			admin_logout();
			
			return FALSE;
		}
		
		// Kiem tra username, password
		$login = admin_login($cookie->username, $cookie->password);
		if ( ! $login['status'])
		{
			// Reset cookie
			admin_logout();
			
			return FALSE;
		}
		
		// Gan trang thai dang nhap
		admin_login_set($login['result']['admin']->id);
		
		return TRUE;
	}
	
	/**
	 * Kiem tra admin da dang nhap hay chua
	 */
	function admin_is_login(&$admin_id_return = 0)
	{
		$CI =& get_instance();
		$ip = $CI->input->ip_address();

		// kiem tra id
		$admin_id = $CI->session->userdata('__admin_id');
		$admin_id = ( ! is_numeric($admin_id)) ? 0 : $admin_id;
		if($admin_id <= 0)
			return FALSE;
		// kiem tra token
		$admin_token = $CI->session->userdata('__admin_token');
		$admin_token_check= md5($admin_id.$ip.config('encryption_key'));
		if($admin_token != $admin_token_check)
			return false;

		$admin_id_return = $admin_id ;
		return  TRUE;
	}
	
	/**
	 * Lay thong tin cua tai khoan admin hien tai
	 */
	function admin_get_account_info()
	{
		$CI =& get_instance();
		
		// Neu chua login
		$admin_id = 0;
		if ( ! admin_is_login($admin_id))
		{
			return FALSE;
		}
		
		// Bien tinh luu thong tin admin hien tai
		static $admin = FALSE;
		
		// Neu thong tin chua duoc get hoac admin_id khong phu hop
		if ( ! isset($admin->id) || $admin->id != $admin_id)
		{
			// Lay thong tin admin
			$CI->load->model('admin_model');
			$admin = $CI->admin_model->get_info($admin_id);
			
			// Neu khong ton tai admin
			if ( ! $admin)
			{
				// Logout tai khoan hien tai
				admin_logout();
				
				return FALSE;
			}
			$CI->load->model('admin_group_model');
			$group =model('admin_group')->get_info($admin->admin_group_id);
			// Lay permissions cua admin hien tai

			$admin->permissions = $CI->admin_group_model->get_permissions($admin->admin_group_id);
			$admin->level =$group->level;
			$admin->group_id =$group->id;
			$admin->group_name =$group->name;


		}
		$admin->is_root =false;
		if($admin->id == 1)
			$admin->is_root =true;

		return $admin;
	}
	
	/**
	 * Admin dang xuat
	 */
	function admin_logout()
	{
		$CI =& get_instance();
		
		// Xoa session
		if ($CI->session->userdata('__admin_id') !== FALSE)
		{
			$CI->session->unset_userdata('__admin_id');
		}
		
		// Xoa cookie
		if (get_cookie('admin') !== FALSE)
		{
			delete_cookie('admin');
		}
	}
	
	
/*
 * ------------------------------------------------------
 *  Admin permission handle
 * ------------------------------------------------------
 */
	/**
	 * Kiem tra quyen truy cap cua admin
	 * @param string $c		Ten controller
	 * @param string $uri	Uri truy cap cua controller
	 */
	function admin_permission($c, $uri)
	{
		//return TRUE;
		//echo '<br>-check per : c='.$c.' - uri='.$uri;
		//pr($uri);
		$admin =admin_get_account_info();
		// neu la root thi khong check
		if($admin->is_root) return TRUE;

		$CI =& get_instance();
		
		// Neu la controller mac dinh
		$c_main = array('home', 'login', 'file', 'md');
		if (in_array($c, $c_main))
		{
			if($c == 'home'){
				if( $uri == 'blank'){
					//pr(1);
					return TRUE;
				}
				else{
					//pr(1);
					return false;

				}
			}

			return TRUE;
		}
		
		// Lay config
		$config = admin_permission_list();
		//pr($config,false);
		// Lay permissions cua admin
		$permissions = $admin->permissions;
		//pr($permissions);
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
	 * Kiem tra admin co quyen truy cap url hay khong
	 * @param string $url	URL can kiem tra
	 */
	function admin_permission_url($url)
	{

		$tmp=admin_parse_url($url);
		return admin_permission($tmp[0], $tmp[1]);
	}
	
	/**
	 * Lay danh sach permission trong config
	 */
	function admin_permission_list()
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

				if (is_array($ps))
				{
					foreach ($ps as $p => $p_i)
					{
						$_list[$c][$p]['name']	= (isset($p_i['name'])) ? $p_i['name'] : '';
						$_list[$c][$p]['uri']	= (isset($p_i['uri'])) ? $p_i['uri'] : $p_i;
					}
				}
			}
		}
		//pr($_list);

		return $_list;
	}
function admin_parse_url($url =null)
{
	$CI =& get_instance();

	// Lay uri
	$uri = url_get_uri($url);
	$uri = mod('seo_url')->get_route_base($uri);
	$uri = explode('/', $uri);

	//pr($CI->router->default_controller);
	// Lay controller

	//$c = (isset($uri[1]) && $uri[1] != '') ? $uri[1] : $CI->router->routes['default_controller'];
	$c = (isset($uri[1]) && $uri[1] != '') ? $uri[1] : $CI->router->default_controller;
	// Lay uri cua controller
	unset($uri[0]);
	unset($uri[1]);
	$u = ( ! count($uri)) ? array('index') : $uri;
	$u = implode('/', $u);

	return [$c,$u];
}