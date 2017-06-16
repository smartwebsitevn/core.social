<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
	/**
	 * Tao ma bao mat tuong ung voi cac bien
	 * @param array 	$params		Danh sach ten va gia tri cua cac bien
	 * @param string 	$key		Key bao mat
	 */
	function security_create_code(array $params, $key = '')
	{
		$CI =& get_instance();
		
		// Sắp xếp dữ liệu theo thứ tự a-z trước khi nối lại
		ksort($params);
		
		// Ghep ten va gia tri cua cac bien lai voi nhau
		$arr = array();
		foreach ($params as $p => $v)
		{
			// Xu ly gia tri
			if (is_bool($v))
			{
				$v = ($v === FALSE) ? 0 : 1;
			}
			$v = strval($v);
			
			// Neu khong ton tai gia tri thi bo qua
			if ( ! strlen($v))
			{
				continue;
			}
			
			$p = strtoupper($p);
			
			$arr[] = $p.'='.$v;
		}
		
		// Them key bao mat vao cac bien
		if ($key != '')
		{
			$arr[] = $key;
		}
		
		// Them ma bao mat cua website vao cac bien
		$arr[] = $CI->config->item('encryption_key');
		
		// Chuyen mang thanh chuoi
		$str = implode('&', $arr);
		
		// Tao ma bao mat
		$security = md5($str);
		
		return $security;
	}
	
	/**
	 * Tao query di kem voi ma bao mat
	 * @param array 	$params			Danh sach ten va gia tri cua cac bien
	 * @param string 	$key			Key bao mat
	 * @param string 	$param_security	Ten bien security
	 */
	function security_create_query(array $params, $key = '', $param_security = '_security')
	{
		$params[$param_security] = security_create_code($params, $key);
		$query = http_build_query($params);
		
		return $query;
	}
	
	/**
	 * Kiem tra ma bao mat trong query (GET method)
	 * @param array 	$params		Danh sach ten cua cac bien
	 * @param string 	$key		Key bao mat
	 * @param string 	$param_security	Ten bien security
	 */
	function security_check_query($params, $key = '', $param_security = '_security')
	{
		$CI =& get_instance();
		
		// Tao mang luu ten va gia tri cua bien
		$param_value = array();
		foreach ($params as $p)
		{
			$v = $CI->input->get($p, FALSE);
			if ($v === FALSE) continue;
			
			$param_value[$p] = $v;
		}
		
		// Tao ma bao mat tuong ung
		$security = security_create_code($param_value, $key);
		
		// Kiem tra ma bao mat
		$security_input = $CI->input->get($param_security);
		if ($security != $security_input)
		{
			return FALSE;
		}
		
		return TRUE;
	}
	
	/**
	 * Ma hoa du lieu
	 */
	function security_encode($data, $key = '')
	{
		$CI =& get_instance();
		
		$data = md5($data);
		
		if ($key != '')
		{
			$data .= $key;
			$data = md5($data);
		}
		
		$data .= $CI->config->item('encryption_key');
		$data = md5($data);
		
		return $data;
	}
	
	/**
	 * Ma hoa, Giai ma du lieu
	 * @param mixed 	$data	Du lieu can xu ly
	 * @param string 	$act	Hanh dong (encode || decode)
	 * @param string 	$mod	Kieu ma hoa (xor || '' => mac dinh)
	 */
	function security_encrypt($data, $act, $mod = '', $is_url_value = FALSE)
	{
		// Neu data la array hoac object
		if (is_array($data) || is_object($data))
		{
			foreach ($data as $p => $v)
			{
				$v = security_encrypt($v, $act, $mod, $is_url_value);
				
				if (is_array($data))
				{
					$data[$p] = $v;
				}
				elseif (is_object($data))
				{
					$data->$p = $v;
				}
			}
			
			return $data;
		}
		
		// Tai library ma hoa
		$CI =& get_instance();
		if ( ! isset($CI->encrypt))
		{
			$CI->load->library('encrypt');
		}
		
		// Xu ly url value
		if ($is_url_value && $act == 'decode')
		{
			$data = str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT);
		}
		
		// Xu ly gia tri cua data
		if ($mod == 'xor')
		{
			$key = $CI->encrypt->get_key();
			if ($act == 'encode')
			{
				$data = $CI->encrypt->_xor_encode($data, $key);
				$data = base64_encode($data);
			}
			elseif ($act == 'decode')
			{
				$data = base64_decode($data);
				$data = $CI->encrypt->_xor_decode($data, $key);
			}
		}
		else 
		{
			$data = $CI->encrypt->{$act}($data);
		}
		
		// Xu ly url value
		if ($is_url_value && $act == 'encode')
		{
			$data = rtrim(strtr($data, '+/', '-_'), '=');
		}
		
		return $data;
	}
	
	/**
	 * Xu ly input
	 * @param mixed $input		Bien dau vao
	 * @param bool 	$is_list	Bien dau vao co phai dang danh sach hay khong
	 */
	function security_handle_input($input, $is_list)
	{
		// Input dang list
		if ($is_list)
		{
			$input = ( ! is_array($input)) ? array($input) : $input;
			foreach ($input as $i => $v)
			{
				$input[$i] = security_handle_input($v, FALSE);
				if ($input[$i] == '')
				{
					unset($input[$i]);
				}
			}
		}
		
		// Input dang 1 gia tri
		else 
		{
			$input = (is_array($input) || is_object($input)) ? '' : (string)$input;
		}
		
		return $input;
	}
	