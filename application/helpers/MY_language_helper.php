<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
	/**
	 * Lay lang
	 *
	 * @param  string  		$key
	 * @param  array|string $replace
	 * @return string
	 */
	function lang($key, $replace = array())
	{
		return call_user_func_array(array(t('lang'), 'line'), func_get_args());
	}
	
	/**
	 * Lay ngon ngu duoi dang ma hoa (dung de luu tru)
	 */
	function lang_encode($line)
	{
		$args = func_get_args();
		foreach ($args as $k => $v)
		{
			// Thay the ki tu ) de tranh bi loi khi giai ma
			$args[$k] = str_replace(')', '*]#', $v); 
		}
		
		$args = serialize($args);
		$line = "lang({$args})";
		
		return $line;
	}
	
	/**
	 * Lay ngon ngu tu ngon ngu ma hoa
	 */
	function lang_decode($str)
	{
		$match = '';
		if (preg_match_all('#lang\(([^\)]+)\)#is', $str, $match))
		{
			foreach ($match[1] as $i => $args)
			{
				$args = @unserialize($args);
				if ( ! is_array($args)) continue;
				
				foreach ($args as $k => $v)
				{
					// Khoi phuc lai ki tu ) da bi thay the khi ma hoa
					$args[$k] = str_replace('*]#', ')', $v); 
				}
				
				$line = call_user_func_array('lang', $args);
				$str = str_replace($match[0][$i], $line, $str);
			}
		}
		
		return $str;
	}
	
	/**
	 * Lay danh sach ngon ngu
	 */
	function lang_get_list()
	{
		static $list = NULL;
		if ($list === NULL)
		{
			// Tai file thanh phan
			$CI =& get_instance();
			$CI->load->model('lang_model');
			
			// Lay lang mac dinh
			// neu ko co luu trong phien thi lay trong cau hinh da thiet lap

			//$default = $CI->lang_model->get_default('id');
			$default_id = setting_get('config-site_language');;
			
			// Lay trong data
			$list_data = $CI->lang_model->get_list();
			// Tao list
			$list = array();
			foreach ($list_data as $row)
			{
				$row->is_default = ($row->id == $default_id) ? TRUE : FALSE;
				$list[$row->id] = $row;
			}
		}
		return $list;
	}
	
	/**
	 * Lay thong tin cua lang
	 */
	function lang_get_info($lang_id)
	{
		// Neu khong ton tai $lang_id
		if ( ! $lang_id)
		{
			return FALSE;
		}
		
		// Lay thong tin tuong ung
		$list = lang_get_list();
		if (isset($list[$lang_id]))
		{
			return $list[$lang_id];
		}
		
		return FALSE;
	}
	
	/**
	 * Them cac thong tin vao thong tin ngon ngu
	 */
	function lang_add_info($row)
	{
		if (isset($row->directory))
		{
			$row->_img = public_url('img/lang/'.$row->directory.'.png');
		}
		
		return $row;
	}
	
	/**
	 * Lay thong tin cua ngon ngu mac dinh
	 */
	function lang_get_default()
	{
		// Lay danh sach
		$list = lang_get_list();
		// Loc ra ngon ngu mac dinh
		foreach ($list as $row)
		{
			if ($row->is_default)
			{
				return $row;
			}
		}
		
		return FALSE;
	}
	
	/**
	 * Lay thong tin cua ngon ngu hien thi hien tai
	 */
	function lang_get_cur()
	{
		// Lay lang_id trong cookie
		$lang_id =t('uri')->langcur_id;
		$lang_id = ( ! is_numeric($lang_id)) ? 0 : $lang_id;
		
		// Lay thong tin tuong ung
		$lang = lang_get_info($lang_id);

		// Neu khong ton tai thi lay theo ngon ngu mac dinh
		if ( ! $lang)
		{
			$lang = lang_get_default();
			
			// Cap nhat cookie
			//set_cookie('lang_id', $lang->id, config('cookie_expire', 'main'));
		}
		//pr($lang);
		return $lang;
	}
	
	/**
	 * Thay doi loai ngon ngu hien thi tren site
	 */
	function lang_change($lang_id)
	{
		// Tai file thanh phan
		$CI =& get_instance();
		$CI->load->model('lang_model');
		
		// Neu ngon ngu nay la ngon ngu hien tai thi bo qua
		$lang_cur = lang_get_cur();
		//pr($lang_cur);
		if (isset($lang_cur->id) && $lang_cur->id == $lang_id)
		{
			return TRUE;
		}
		
		// Kiem tra lang
		$lang = $CI->lang_model->get_info_active($lang_id, 'id');
		if ( ! $lang)
		{
			return FALSE;
		}

		// Luu cookie
		set_cookie('lang_id', $lang_id, config('cookie_expire', 'main'));
		
		return TRUE;
	}

	function lang_get_cache($lang_dir,$file){
		t()->load->driver('cache');
		$lang_cache = str_replace('/','.', $file);
		$lang_cache = $lang_dir.'/'.$lang_cache;
		$lang = t()->cache->file->get($lang_cache);
		return $lang;
	}

	function lang_set_cache($lang_dir,$file,$values){
		t()->load->driver('cache');
		$lang_cache = str_replace('.php','', $file);
		$lang_cache = str_replace('/','.', $lang_cache);
		$lang_cache =$lang_dir.'/'.$lang_cache;
		//echo "<br>==$lang_cache:".$lang_cache;
		t()->cache->file->save($lang_cache, $values, config('cache_expire_long', 'main'));

		return $values;
	}
	function lang_del_cache($lang_dir){
		$path = 'application/cache/'.$lang_dir;
		delete_files($path,true);
		@rmdir($path);
	}

	/**
	 * Tao array co key la cac keys lang, gia tri la lang tuong ung cua cac key
	 *
	 * @param array  $keys
	 * @param string $prefix
	 * @param string $suffix
	 * @return array
	 */
	function lang_map(array $keys, $prefix = '', $suffix = '')
	{
		$list = [];

		foreach ($keys as $key)
		{
			$list[$key] = lang($prefix.$key.$suffix);
		}

		return $list;
	}