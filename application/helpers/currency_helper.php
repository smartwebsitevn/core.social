<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * ------------------------------------------------------
 *  Main fun
 * ------------------------------------------------------
 */
	/**
	 * Lay danh sach
	 */
	function currency_get_list()
	{
		static $list = NULL;
		if ($list === NULL)
		{
			// Tai file thanh phan
			$CI =& get_instance();
			$CI->load->model('currency_model');
			
			// Lay tien te mac dinh
			$default 	= $CI->currency_model->get_default('id');
			$default_id = ($default) ? $default->id : 0;
			
			// Lay list trong data
			$list_data = $CI->currency_model->get_list();
			
			// Tao list
			$list = array();
			foreach ($list_data as $row)
			{
				$row->value = floatval($row->value);
				$row->is_default = ($row->id == $default_id) ? TRUE : FALSE;
				
				$list[$row->id] = $row;
			}
		}
		
		return $list;
	}
	
	/**
	 * Lay thong tin cua currency
	 */
	function currency_get_info($currency_id)
	{
		// Neu khong ton tai $currency_id
		if ( ! $currency_id)
		{
			return FALSE;
		}
		
		// Lay thong tin tuong ung
		$list = currency_get_list();
		if (isset($list[$currency_id]))
		{
			return $list[$currency_id];
		}
		
		return FALSE;
	}
	
	/**
	 * Lay thong tin cua loai tien te mac dinh
	 */
	function currency_get_default()
	{
		// Lay danh sach
		$list = currency_get_list();
		
		// Loc ra tien te mac dinh
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
	 * Lay thong tin cua tien te hien thi hien tai
	 */
	function currency_get_cur()
	{
		$currency_id = config('currency_multi', 'main') ? get_cookie('currency_id') : 0;
		
		$currency = currency_get_info($currency_id);
		
		return $currency ?: currency_get_default();
	}
	
	/**
	 * Thay doi loai tien te hien thi tren site
	 */
	function currency_change($currency_id)
	{
		// Tai file thanh phan
		$CI =& get_instance();
		$CI->load->model('currency_model');
		
		// Neu tien te nay la tien te hien tai thi bo qua
		$currency_cur = currency_get_cur();
		if (isset($currency_cur->id) && $currency_cur->id == $currency_id)
		{
			return TRUE;
		}
		
		// Kiem tra currency
		$currency = $CI->currency_model->get_info_active_show($currency_id, 'id');
		if ( ! $currency)
		{
			return FALSE;
		}
		
		// Luu cookie
		set_cookie('currency_id', $currency_id, config('cookie_expire', 'main'));
		
		return TRUE;
	}
	
	/**
	 * Xu ly tien dau vao
	 * @param float $amount 	So tien
	 * @param bool 	$natural	Co chuyen amount ve so nguyen duong hay khong
	 * @param int 	$decimal	So thap phan lam tron
	 */
	function currency_handle_input($amount, $natural = FALSE, $decimal = NULL)
	{
		// Loai bo ki tu ','
		if (strpos($amount, ',') !== FALSE)
		{
			$amount = str_replace(',', '', $amount);
		}
		
		// Gan dinh dang float
		$amount = floatval($amount);
		
		// Chuyen ve so nguyen duong
		if ($natural)
		{
			$amount = max(0, $amount);
		}
		
		// Lam tron
		if ($decimal !== NULL)
		{
			$amount = round($amount, $decimal);
		}
		
		return $amount;
	}
	
	
/*
 * ------------------------------------------------------
 *  Convert amount
 * ------------------------------------------------------
 */
	/**
	 * Chuyen doi tien tu tien te mac dinh sang loai tien te khac
	 * @param float	$amount			So tien can chuyen
	 * @param int	$currency_id	Loai tien te muon chuyen sang
	 */
	function currency_convert_amount($amount, $currency_id)
	{
		// Lay thong tin cua loai tien te muon chuyen sang
		$currency = currency_get_info($currency_id);
		
		// Neu khong ton tai thi tra lai gia tri
		if ( ! $currency)
		{
			return $amount;
		}
		
		// Chuyen doi tien
		$amount = floatval($amount);
		$amount = $amount / floatval($currency->value);
		
		// Lam tron tien
		$amount = round($amount, $currency->decimal);
		
		return $amount;
	}
	
	/**
	 * Chuyen doi tien tu 1 loai tien te ve tien te mac dinh
	 * @param float $amount			So tien can chuyen
	 * @param int 	$currency_id	Loai tien te cua amount
	 */
	function currency_convert_amount_default($amount, $currency_id)
	{
		// Lay thong tin cua loai tien te
		$currency = currency_get_info($currency_id);
		
		// Neu khong ton tai thi tra lai gia tri
		if ( ! $currency)
		{
			return $amount;
		}
		
		// Chuyen doi tien
		$amount = floatval($amount);
		$amount = $amount * floatval($currency->value);
		
		// Lam tron tien theo tien te mac dinh
		$currency_default = currency_get_default();
		$amount = round($amount, $currency_default->decimal);
		
		return $amount;
	}
	
	/**
	 * Chuyen doi tien giua 2 loai tien te
	 * @param float $amount				So tien can chuyen
	 * @param int 	$from_currency_id	Loai tien te can chuyen
	 * @param int 	$to_currency_id		Loai tien te muon chuyen
	 */
	function currency_convert_amount_other($amount, $from_currency_id, $to_currency_id)
	{
		// Lay thong tin cua tien te
		$from_currency 	= currency_get_info($from_currency_id);
		$to_currency 	= currency_get_info($to_currency_id);
		
		// Neu khong ton tai thi tra lai gia tri
		if ( ! $from_currency || ! $to_currency)
		{
			return $amount;
		}
		
		// Chuyen doi tien
		$amount = floatval($amount);
		$amount = $amount * floatval($from_currency->value / $to_currency->value);
		
		// Lam tron tien
		$amount = round($amount, $to_currency->decimal);
		
		return $amount;
	}
	
	/**
	 * Chuyen doi tien tu tien te mac dinh sang loai tien te hien thi hien tai
	 * @param float $amount	So tien can chuyen
	 */
	function currency_convert_amount_cur($amount)
	{
		$currency = currency_get_cur();
		
		return currency_convert_amount($amount, $currency->id);
	}
	
	
/*
 * ------------------------------------------------------
 *  Format amount
 * ------------------------------------------------------
 */
	/**
	 * Them thong tin cua tien te vao amount
	 * @param float	$amount				So tien hien tai
	 * @param int	$currency_id		Loai tien te muon them thong tin
	 * @param bool	$add_currency_info	Co cho phep them thong tin cua tien te vao khong
	 */
	function currency_format_amount($amount, $currency_id = 0, $add_currency_info = TRUE)
	{
		// Lay thong tin cua tien te
		$currency = currency_get_info($currency_id);
		
		// Neu khong ton tai thi lay theo loai tien te hien tai
		if ( ! $currency)
		{
			$currency = currency_get_cur();
		}
		
		// Chuyen doi dinh dang cua amount
		$amount = currency_format_amount_decimal($amount, $currency->decimal);
		
		// Them thong tin cua tien te vao amount
		if ($add_currency_info)
		{
			$amount = $currency->symbol_left.$amount.$currency->symbol_right;
		}
		
		return $amount;
	}
	
	/**
	 * Them thong tin cua tien te mac dinh vao amount
	 * @param float	$amount				So tien hien tai
	 * @param bool	$add_currency_info	Co cho phep them thong tin cua tien te vao khong
	 */
	function currency_format_amount_default($amount, $add_currency_info = TRUE)
	{
		// Lay thong tin cua tien te mac dinh
		$currency = currency_get_default();
		
		return currency_format_amount($amount, $currency->id, $add_currency_info);
	}
	
	/**
	 * Chuyen doi dinh dang dong thoi lam tron tien
	 * @param float	$amount		So tien hien tai
	 * @param int	$decimal	So thap phan lam tron
	 */
	function currency_format_amount_decimal($amount, $decimal = 0)
	{
		$amount = floatval($amount);
		$amount = number_format($amount, $decimal);
		$amount = preg_replace('/\.(0+?)$/is', '', $amount);
		$amount = preg_replace('/\.(\d+?)(0+?)$/is', '.$1', $amount);
		
		return $amount;
	}
	
	/**
	 * Chuyen doi tien va dinh dang tu tien te mac dinh sang loai tien te khac
	 * @param float	$amount			So tien can chuyen
	 * @param int	$currency_id	Loai tien te muon chuyen sang
	 */
	function currency_convert_format_amount($amount, $currency_id = 0)
	{
		// Neu khong ton tai thi gan bang tien te hien tai
		if ( ! $currency_id)
		{
			$currency_cur 	= currency_get_cur();
			$currency_id 	= $currency_cur->id;
		}
		
		// Chuyen doi tien va dinh dang
		$amount = currency_convert_amount($amount, $currency_id);
		$amount = currency_format_amount($amount, $currency_id);
		
		return $amount;
	}
	