<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
	/**
	 * Tao limit cho page
	 * 
	 * @param int $total			Tong so row
	 * @param int $page_size		So row tren 1 page
	 * @param string $page_query	Ten bien xac dinh page hien tai trong $_GET
	 * @return array($limit, $page_size)
	 */
	function make_limit_page($total, $page_size = 0, $page_query = 'per_page')
	{
		$page_size = ( ! $page_size) ? config('list_limit', 'main') : $page_size;
		
		$limit = t('input')->get($page_query);
		$limit = max(0, min($limit, get_limit_page_last($total, $page_size)));
		
		return array($limit, $page_size);
	}
	
	/**
	 * Lay limit cua trang cuoi cung (trong phan chia trang)
	 */
	function get_limit_page_last($total, $page_size)
	{
		$total_page = ceil($total/$page_size);
		$limit_last = ($total_page - 1) * $page_size;
		$limit_last = max(0, $limit_last);
		
		return $limit_last;
	}
	
	/**
	 * Chuyen so thanh chuoi voi chieu dai nhat dinh
	 */
	function num_pad($num, $len, $precision = 0)
	{
		$num = floatval($num);
		$num = round($num, $precision);
		$num *= pow(10, $precision);
		
		return str_pad($num, $len, '0', STR_PAD_LEFT);
	}
	
	/**
	 * Chuyen du lieu sang kieu float
	 */
	function fv($n)
	{
		return (float)(string)$n;;
	}
	
	/**
	 * Random so theo chieu dai
	 */
	function random_num($len)
	{
		$n = '';
		while (strlen($n) != $len)
		{
			$n = random_string('numeric', $len);
			$n = preg_replace('#^0+#', '', $n);
		}
		
		return $n;
	}

	/**
	 * Lay fake id (neu chua ton tai thi tu dong tao)
	 */
	function fake_id_get($key)
	{
		$CI =& get_instance();
		
		$session_name = 'fake_id_'.$key;
		
		$fake_id = $CI->session->userdata($session_name);
		if ( ! $fake_id)
		{
			$fake_id = '-'.time().random_string('numeric', 32);
			$fake_id = substr($fake_id, 0, 32); // Toi da 32 ki tu
			$CI->session->set_userdata($session_name, $fake_id);
		}
		
		return $fake_id;
	}
	
	/**
	 * Xoa fake id
	 */
	function fake_id_del($key)
	{
		$CI =& get_instance();
		
		$session_name = 'fake_id_'.$key;
		
		$CI->session->unset_userdata($session_name);
	}
	
	/**
	 * Tinh fee
	 * 
	 * @param float $amount
	 * @param array $opt
	 * @return float
	 */
	function get_fee($amount, array $opt)
	{
	    $amount 		= (float) $amount;
	    $fee_constant 	= (float) array_get($opt, 'constant');
	    $fee_percent 	= (float) array_get($opt, 'percent');
	    $fee_min 		= (float) array_get($opt, 'min');
	    $fee_max 		= (float) array_get($opt, 'max');
	    
        $fee = $fee_constant + ($amount * $fee_percent * 0.01);
        
        $fee = max($fee_min, $fee);
        
        if ($fee_max)
        {
            $fee = min($fee, $fee_max);
        }
        
        return $fee;
	}
	
	/**
	 * Kiem tra amount
	 * 
	 * @param float $amount
	 * @param array $opt
	 * @return boolean
	 */
	function valid_amount($amount, array $opt)
	{
		$amount 	= (float) $amount;
		$amount_min = (float) array_get($opt, 'min');
		$amount_max = (float) array_get($opt, 'max');
		
		return (
			$amount > 0
			&& $amount >= $amount_min
			&& ( ! $amount_max || $amount <= $amount_max)
		);
	}

