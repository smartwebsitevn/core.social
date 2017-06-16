<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * ------------------------------------------------------
 *  CI function
 * ------------------------------------------------------
 */
	/**
	 * Tao url
	 * @param string 	$uri	Uri
	 * @param array 	$opt	Cac thuoc tinh
	 * 	Bao gom cac bien:
	 * 		seo 	= TRUE :		Co xu ly seo hay khong
	 * 		suffix 	= TRUE :		Co them suffix vao hay khong
	 */
	function site_url($uri = '', array $opt = array())
	{
		$CI =& get_instance();
		
		// Tach query ra khoi uri neu co
		$uri 	= explode('?', $uri, 2);
		$query 	= (isset($uri[1])) ? $uri[1] : FALSE;
		$uri	= $uri[0];
		
		// Xu ly input
		$uri = trim($uri, '/');
		$opt = set_default_value($opt, 'seo', TRUE);
		$opt = set_default_value($opt, 'suffix', TRUE);
		
		// Xu ly seo
		if ($opt['seo'])
		{
			// su ly seo dong (CSDL)
			$uri = $CI->mod->seo_url->handle_uri($uri);
		}

		
		// Xu ly uri theo mobile
		if (isset($CI->uri->mobile))
		{
			$uri = ($uri == 'mobile') ? '' : $uri;
			$uri = preg_replace('#^mobile/#i', '', $uri);
			$uri = $CI->uri->segment(1).'/'.$uri;
		}
		// Su ly lang tren uri
		$langcur = $CI->uri->langcur;
		if($langcur)
			$uri =$langcur .'/'.$uri;
		// Tao url
		$url = $CI->config->site_url($uri);
		
		// Neu url khong bao gom url_suffix
		$url_suffix = config('url_suffix', '');
		if ( ! $opt['suffix'] && $url_suffix)
		{
			$url = preg_replace('#'.preg_quote($url_suffix).'$#i', '', $url);
		}
		
		// Xu ly https
		$use_https = FALSE;
		if(config('use_https', 'main'))
			$use_https = true;
		//$uri_https = config('uri_https', 'main');
		/*if (is_array($uri_https))
		{
			foreach ($uri_https as $p)
			{
				$p_last = right($p, 1);
				$p = str_replace('*', '', $p);
				
				if (
					($p_last != '*' && $uri == $p) ||
					($p_last == '*' && preg_match('#^'.$p.'#i', $uri))
				)
				{
					$use_https = TRUE;
					break;
				}
			}
		}
		elseif ($uri_https == '*')
		{
			$use_https = TRUE;
		}*/
		
		$url = url_https($url, $use_https);
		
		// Them query vao url
		if ($query)
		{
			$url .= '?'.$query;
		}
		
		return $url;
	}
	
	/**
	 * Lay url hien tai
	 * @param bool 	$include_query		Co lay query hay khong
	 * @param int 	$page_uri			Lay den uri nao (Neu khai bao thi se loai bo cac uri sau uri khai bao)
	 */
	function current_url($include_query = FALSE, $page_uri = 0)
	{
		$CI =& get_instance();
		
		// Lay uri hien tai
		$uri = $CI->uri->segment_array();
		if ($page_uri)
		{
			foreach ($uri as $i => $v)
			{
				if ($i >= $page_uri)
				{
					unset($uri[$i]);
				}
			}
		}
		$uri = implode('/', $uri);
		
		// Kiem tra co ton tai url_suffix tren url hay khong
		$redirect_url 	= $CI->input->server('REDIRECT_URL');
		$suffix 		= url_use_suffix($redirect_url);
		
		// Tao url
		$url = site_url($uri, array('suffix' => $suffix, 'seo' => FALSE));
		
		// Them query vao url
		$query = $CI->input->server('QUERY_STRING', FALSE);
		if ($include_query && $query != '')
		{
			$url .= '?'.$query;
		}
		
		return $url;
	}
	
	/**
	 * Tao duong dan cua cac file trong thu muc public
	 */
	function public_url($uri = '')
	{
		$url = base_url('public/'.$uri);
		
		return $url;
	}
	
	/**
	 * Base url
	 */
	function base_url($uri = '')
	{
		$CI =& get_instance();
		
		$url = $CI->config->base_url($uri);
		$url = url_https($url, url_is_https());
		
		return $url;
	}
	
	/**
	 * Tao duong dan cua file upload
	 * @param string 	$file_name	Ten file
	 * @param bool 		$server		File co duoc luu tren server hay khong
	 */
	function upload_url($file_name, $server = TRUE)
	{

		// Lay config
		$config = config('upload', 'main');
		
		// Tao url
		$file_name = 'public/'.$file_name;
		$url = base_url($config['folder'].'/'.$file_name);
		
		// Neu file duoc luu tren server khac
		if ($server && $config['server']['status'])
		{
			$url = $config['server']['url'].$file_name;
		}
		
		return $url;
	}
	
	/**
	 * Tao url cho row cua mod
	 * 
	 * @param string $mod
	 * @param object $row
	 * @return object
	 */
	function mod_url($mod, $row)
	{
		return t('mod')->{$mod}->url($row);
	}

	function mod_handle_uri($uri)
	{
		return $uri;
		//pr($uri);
		$mod=explode('/',$uri);
		//if($mod[0] != 'movie_list') return $uri;
		//echo '<br>'.$uri;
		switch ($mod[0]) {
			case 'movie_list': {
				if (isset($mod[1])) {
					if ($mod[1] == "tag") {
						$uri = 'movie_list/caca';
					}
				}
				break;
			}
		}
		return $uri;
	}

	
/*
 * ------------------------------------------------------
 *  Custom function
 * ------------------------------------------------------
 */
	/**
	 * Xu ly url https
	 */
	function url_https($url, $https)
	{
		if ($https)
		{
			$url = preg_replace('#^http://#i', 'https://', $url);
		}
		else 
		{
			$url = preg_replace('#^https://#i', 'http://', $url);
		}
		
		return $url;
	}
	
	/**
	 * Kiem tra url co phai https hay khong
	 */
	function url_is_https($url = '')
	{
		$CI =& get_instance();
		
		// Lay theo url hien tai
		if ($url == '')
		{
			return (
				$CI->input->server('HTTPS') == 'on' ||
				$CI->input->server('SERVER_PORT') == 443 ||
				$CI->input->server('REQUEST_SCHEME') == 'https' ||
				$CI->input->server('HTTP_X_FORWARDED_PROTO') == 'https'
			) ? TRUE : FALSE;
		}
		
		// Url xac dinh
		return (preg_match('#^https#i', $url)) ? TRUE : FALSE;
	}
	
	/**
	 * Lay url trang login bao gom bien tro ve trang hien tai
	 */
	function url_login_return($mod = 'user')
	{
		$url_cur 	= current_url(TRUE);
		$query 		= security_create_query(array('return' => $url_cur), 'login_return');
		$url 		= site_create_url($mod.'_page', 'login').'?'.$query;
		
		return $url;
	}
	
	/**
	 * Chuyen den trang dang nhap sau do chuyen ve trang hien tai
	 */
	function redirect_login_return($mod = 'user')
	{
		$CI =& get_instance();
		
		// Luu current url vao session
		$url_cur = current_url(TRUE);
		$CI->session->set_userdata('url_return', $url_cur);
		
		// Chuyen den trang login
		redirect(site_create_url($mod.'_page', 'login'));
	}
	
	/**
	 * Lay uri cua url
	 * @param string $url	URL hien tai
	 */
	function url_get_uri($url)
	{
		// Loai bo query
		$uri = explode('?', $url, 2);
		$uri = $uri[0];
		$uri = url_https($uri, FALSE); // Loai bo https neu co
		
		// Loai bo base_url
		$base_url 	= config('base_url', 'main');
		$base_url 	= url_https($base_url, FALSE); // Loai bo https neu co
		$uri = preg_replace('#^'.preg_quote($base_url).'#i', '', $uri);
		
		// Loai bo url_suffix
		$url_suffix = config('url_suffix', '');
		if ($url_suffix)
		{
			$uri = preg_replace('#'.preg_quote($url_suffix).'$#i', '', $uri);
		}
		
		// Xu ly uri
		$uri = trim($uri, '/');
		
		return $uri;
	}
	
	/**
	 * Them uri vao url
	 */
	function url_add_uri($url, $uri)
	{
		// Lay query
		$query = explode('?', $url, 2);
		$query = (isset($query[1])) ? $query[1] : FALSE;
		
		// Them uri
		$uri_cur = url_get_uri($url);
		$uri_cur .= '/'.$uri;
		
		// Tao url moi
		$url = site_url($uri_cur, array('suffix' => url_use_suffix($url), 'seo' => FALSE));
		if ($query)
		{
			$url .= '?'.$query;
		}
		
		return $url;
	}
	
	/**
	 * Tao query truy cap
	 * @param 	array 	$input			Du lieu dau vao
	 * @param 	boolean $return_array	Tra ve array hay khong
	 * @return 	mixed	Query da loai bo cac bien khong co gia tri
	 */
	function url_build_query(array $input, $return_array = FALSE)
	{
		$query = array();
		foreach ($input as $f => $v)
		{
			$v = ( ! is_array($v)) ? (string)$v : $v;
			if (empty($v) && $v !== '0')
			{
				continue;
			}
			
			$query[$f] = $v;
		}
		
		return ($return_array) ? $query : http_build_query($query);
	}
// lay query cua url hien thoi
function url_get_query($not_include_key=array())
{
	$url = parse_url(current_url(true));
	if(!isset($url['query']) || !$url['query'])
		return '';
	parse_str($url['query'], $query);
	// loai bo cac param yeu cau
	if($query && $not_include_key){
		foreach($query as $k=>$v){
			if(in_array($k,$not_include_key)){
				unset($query[$k]);
			}
		}
	}
	//if(isset($query['per_page']))unset($query['per_page']);
	return  http_build_query($query);
}
	/**
	 * Kiem tra url co su dung suffix hay khong
	 */
	function url_use_suffix($url)
	{
		// Loai bo query khoi url
		$url = explode('?', $url, 2);
		$url = $url[0];
		
		// Kiem tra suffix tren url
		$url_suffix	= config('url_suffix', '');
		$use_suffix	= (
						$url_suffix && 
						preg_match('#'.preg_quote($url_suffix).'$#i', $url)
					) ? TRUE : FALSE;
		
		return $use_suffix;
	}
	
	/**
	 * Xoa suffix ra khoi url
	 */
	function url_remove_suffix($url)
	{
		// Tach query
		$url = explode('?', $url, 2);
		
		// Loai bo url_suffix
		$url_suffix = config('url_suffix', '');
		if ($url_suffix)
		{
			$url[0] = preg_replace('#'.preg_quote($url_suffix).'$#i', '', $url[0]);
		}
		
		// Khoi phuc query
		$url = implode('?', $url);
		
		return $url;
	}
	
	/**
	 * Lay order sap xep (GET method)
	 * @param array 	$fields			Thong tin cac field can sap xep
	 * @param string 	$base_url		Url chinh
	 * @param string 	$param			Ten bien order trong query
	 * @param string 	$delimiter		Dau phan cach giua cac thong tin sap xep
	 * @return array(field, flag, query)
	 */
	function url_get_order(array &$fields, $base_url, $param = '_order', $delimiter = ':')
	{
		$CI =& get_instance();
		
		// Xu ly thong tin dau vao cua $fields
		foreach ($fields as $f => $v)
		{
			$v = ( ! is_array($v)) ? array() : $v;
			$v['name'] = ( ! isset($v['name'])) ? $f : $v['name'];
			$v['params'] = ( ! isset($v['params'])) ? array() : $v['params'];
			$v['status_default'] = ( ! isset($v['status_default'])) ? 'asc' : $v['status_default'];
			
			$fields[$f] = $v;
		}
		
		// Lay order hien tai
		$fs = array_keys($fields);
		
		$order = $CI->input->get($param);
		$order = explode($delimiter, $order);
		$order[0] = ( ! in_array($order[0], $fs)) ? $fs[0] : $order[0];
		$order[1] = ( ! isset($order[1]) || ! in_array($order[1], array('asc', 'desc'))) ? $fields[$order[0]]['status_default'] : $order[1];
		$order[2] = http_build_query(array($param => $order[0].$delimiter.$order[1]));
		
		// Tao thong tin order cho $fields
		foreach ($fields as $f => $v)
		{
			$v['status'] = ($f == $order[0]) ? $order[1] : '';
			$v['url_status'] = ( ! $v['status'] || $v['status'] == 'desc') ? 'asc' : 'desc';
			
			$q = $v['params'];
			$q[$param] = $v['name'].$delimiter.$v['url_status'];
			$v['url'] = $base_url.'&'.http_build_query($q);
			
			$fields[$f] = $v;
		}
		
		return $order;
	}
	
	/**
	 * Them bien url_return vao url
	 */
	function url_add_return($url, $url_return = '')
	{
		$url_return 	= ( ! is_array($url_return)) ? array('url_return', $url_return) : $url_return;
		$url_return[1] 	= ( ! $url_return[1]) ? current_url(TRUE) : $url_return[1];
		
		$query = security_create_query(array($url_return[0] => $url_return[1]), 'url_return', 'url_return_security');
		$url .= ( ! strpos($url, '?')) ? '?'.$query : '&'.$query;
		
		return $url;
	}
	
	/**
	 * Lay gia tri cua bien url_return trong GET method
	 */
	function url_get_return($default_val = '', $param = 'url_return')
	{
		$CI =& get_instance();
		
		$val = (security_check_query(array($param), 'url_return', 'url_return_security')) ? $CI->input->get($param, FALSE) : '';
		$val = ( ! $val) ? $default_val : $val;
		
		return $val;
	}
	
	/**
	 * Kiem tra url co phai la cua site hay khong
	 */
	function url_site_valid($url)
	{
		$base_url 	= config('base_url', 'main');
		$base_url 	= url_https($base_url, FALSE);
		$url 		= url_https($url, FALSE);
		
		return (preg_match('#^'.preg_quote($base_url).'#i', $url)) ? TRUE : FALSE;
	}
	
	/**
	 * Kiem tra url co phu hop voi cac url dieu kien trong list hay khong
	 * @param string	$url		URL can kiem tra
	 * @param array		$list		List cac url dieu kien
	 * @param string 	$url_match	Url dieu kien phu hop
	 * 	Url dieu kien co cac dang sau:
	 * 		url 	: Kiem tra tuong doi (kiem tra url hien tai va url_base)
	 * 		"url"	: Kiem tra chinh xac (chi kiem tra voi url hien tai)
	 * 		url/*	: Kiem tra voi cac trang con
	 */
	function url_in_list($url, $list, &$url_match = '')
	{
		foreach ((array) $list as $v)
		{
			// Lay danh sach cac cap url can kiem tra
			$l = array();
			$l[] = array(trim($v, '"'), $url);
			if ((left($v) != '"' || right($v) != '"')) // Neu khong phai la kiem tra chinh xac
			{
				$v_b 	= url_get_base($v);
				$url_b 	= url_get_base($url);
				if ($v_b != $v || $url_b != $url)
				{
					$l[] = array($v_b, $url_b);
				}
			}
			
			// Thuc hien kiem tra cac cap url
			foreach ($l as $r)
			{
				$u1 = url_remove_suffix($r[0]);
				$u2 = url_remove_suffix($r[1]);

				$u1 = url_https($u1, 0);
				$u2 = url_https($u2, 0);
				
				// Lay che do kiem tra
				$mod = (right($u1) == '*') ? 'like' : 'equal';
				$u1 = trim($u1, '*');
				
				// Kiem tra url
				if (
					($mod == 'equal' && strtolower($u1) == strtolower($u2)) ||
					($mod == 'like' && preg_match('#^'.preg_quote($u1).'#i', $u2))
				)
				{
					$url_match = $v;
					return TRUE;
				}
			}
		}
		
		return FALSE;
	}
	
	/**
	 * Lay url co ban
	 * @param string $url
	 */
	function url_get_base($url)
	{
		$CI =& get_instance();
		
		// Neu url la url co ban
		$uri 	= url_get_uri($url);
		$route 	= $CI->mod->seo_url->get_route_base($uri);
		if ($uri == $route)
		{
			return $url;
		}
		
		// Lay query
		$query = explode('?', $url, 2);
		$query = (isset($query[1])) ? $query[1] : FALSE;
		
		// Tao url_base
		$url_base = site_url($route, array('suffix' => url_use_suffix($url), 'seo' => FALSE));
		if ($query)
		{
			$url_base .= '?'.$query;
		}
		
		return $url_base;
	}
	
	/**
	 * Kiem tra url nay co phai la url cha cua url kia hay khong
	 * 
	 * @param string $haystack
	 * @param string $needle
	 * @return boolean
	 */
	function url_is_parent($haystack, $needle = null)
	{
		if (url_equal($haystack, $needle))
		{
			return true;
		}
		
		$haystack = url_remove_suffix($haystack);
		$haystack = rtrim($haystack, '/') . '/*';
		
		$needle = $needle ?: current_url();
		
		return url_in_list($needle, $haystack);
	}
	
	/**
	 * Lay url cha cua 1 url tu danh sach
	 * 
	 * @param array|string $haystacks
	 * @param string $needle
	 * @return string|false
	 */
	function url_get_parent($haystacks, $needle = null)
	{
		$haystacks = (array) $haystacks;
		
		// Lay ra cac url la cha cua $needle
		$haystacks = array_where($haystacks, function($i, $haystack) use ($needle)
		{
			return url_is_parent($haystack, $needle);
		});
		
		// Sap xep danh sach theo chieu giam dan cua chieu dai url
		$haystacks = array_sort($haystacks, function($haystack)
		{
			return mb_strlen($haystack) * -1;
		});
		
		return head($haystacks);
	}
	
	/**
	 * Kiem tra 2 url co giong nhau hay khong
	 * 
	 * @param string $haystack
	 * @param string $needle
	 * @return boolean
	 */
	function url_equal($haystack, $needle = null)
	{
		$needle = $needle ?: current_url();
		
		return url_in_list($needle, $haystack);
	}
	
	/**
	 * Kiem tra url co phai la home hay khong
	 * 
	 * @param string $url
	 * @return boolean
	 */
	function url_is_home($url = null)
	{
		$url = $url ?: current_url();
		
		return ( ! url_get_uri($url));
	}

/**
 * Phan tich url
 *
 * @param string $url
 * @return boolean
 */
function url_parse($url = null)
{
	static $urls=array();
	$umd5= md5($url);
	if(isset($urls[$umd5]))
		return $urls[$umd5];

	// Lay uri
	$uri = url_get_uri($url);
	//$uri = mod('seo_url')->get_route_base($uri);
	$uri = explode('/', $uri);
	$c = (isset($uri[1]) && $uri[1] != '') ? $uri[1] : t('router')->default_controller;
	unset($uri[0]);
	unset($uri[1]);
	$uri = array_values($uri);
	$u = ( ! count($uri)) ? array('index') : $uri;
	$obj=new stdClass();
	$obj->controller =$c  ;
	$obj->method = $u[0];
	$obj->value = isset($u[1]);
	//$obj->query ='';
	$urls[$umd5]=$obj;
	return $obj;
}
