<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * ------------------------------------------------------
 *  Main fun
 * ------------------------------------------------------
 */
	/**
	 * Kiem tra su ton tai cua payment
	 */
	function payment_exists($payment)
	{
		if (!$payment) return FALSE;
		$payment = ucfirst($payment);
		$url = APPPATH.'libraries/payment/'.$payment.'_payment'.EXT;
		
		return (file_exists($url)) ? TRUE : FALSE;
	}
	
	/**
	 * Kiem tra payment da duoc cai dat hay chua
	 */
	function payment_installed($payment)
	{
		if (!$payment) return FALSE;
		
		// Tai file thanh phan
		$CI	=& get_instance();
		$CI->load->model('payment_model');
		
		// Lay danh sach cac payment da cai dat
		$payment_installed = $CI->payment_model->get_list_installed();
		
		return (in_array($payment, $payment_installed)) ? TRUE : FALSE;
	}
	
	/**
	 * Kiem tra payment co dang hoat dong hay khong
	 */
	function payment_active($payment)
	{
		if (!$payment) return FALSE;
		
		$CI	=& get_instance();
		
		// Kiem tra payment da duoc cai dat hay chua
		if (!payment_installed($payment))
		{
			return FALSE;
		}
		
		// Kiem tra trang thai
		$status = $CI->payment_model->get_setting($payment, 'status');
		if ($status == config('status_off', 'main'))
		{
			return FALSE;
		}
		
		return TRUE;
	}
	
	
/*
 * ------------------------------------------------------
 *  Convert amount
 * ------------------------------------------------------
 */
	/**
	 * Chuyen doi tien tu tien te mac dinh sang tien te cua payment
	 * @param float		$amount		So tien muon chuyen doi
	 * @param string	$payment	Ten payment
	 */
	function payment_convert_amount($amount, $payment)
	{
		// Tai file thanh phan
		$CI	=& get_instance();
		$CI->load->model('payment_model');
		
		// Lay currency cua payment
		$currency = $CI->payment_model->get_currency($payment);
		
		// Chuyen doi tien
		$amount = currency_convert_amount($amount, $currency);
		
		return $amount;
	}
	
	/**
	 * Chuyen doi tien tu tien te cua payment sang tien te mac dinh
	 * @param float		$amount		So tien muon chuyen doi
	 * @param string	$payment	Ten payment
	 */
	function payment_convert_amount_default($amount, $payment)
	{
		// Tai file thanh phan
		$CI	=& get_instance();
		$CI->load->model('payment_model');
		
		// Lay currency cua payment
		$currency = $CI->payment_model->get_currency($payment);
		
		// Chuyen doi tien
		$amount = currency_convert_amount_default($amount, $currency);
		
		return $amount;
	}
	
	/**
	 * Lay phi cua giao dich qua payment
	 * @param float		$amount 	So tien (da quy doi sang don vi tien te cua payment)
	 * @param string	$payment 	Ten payment
	 */
	function payment_get_cost($amount, $payment)
	{
		$CI	=& get_instance();
		
		// Lay phi giao dich tu payment
		$amount = floatval($amount);
		$cost = $CI->payment->{$payment}->get_cost($amount);
		
		// Lam tron theo so thap phan cua loai tien te ma payment dang su dung
		if ($cost)
		{
			$CI->load->model('payment_model');
			
			$currency_id = $CI->payment_model->get_currency($payment);
			$currency = currency_get_info($currency_id);
			
			if ($currency)
			{
				$cost = round($cost, $currency->decimal);
			}
		}
		
		return $cost;
	}
	
	/**
	 * Lay so tien giao dich qua payment (bao gom ca phi)
	 * @param float		$amount		So tien can thanh toan (don vi la tien te mac dinh)
	 * @param string	$payment	Cong thanh toan
	 */
	function payment_get_amount($amount, $payment)
	{
		// Chuyen doi tien sang loai tien cua payment
		$amount = payment_convert_amount($amount, $payment);
		
		// Lay phi giao dich
		$cost = payment_get_cost($amount, $payment);
		
		// Tinh amount
		$amount += $cost;
		$amount = fv($amount);
		
		return $amount;
	}
	
	/**
	 * Them thong tin loai tien te cua payment vao amount
	 * @param float		$amount				So tien hien tai
	 * @param string	$payment			Cong thanh toan
	 * @param bool		$add_currency_info	Co cho phep them thong tin cua tien te vao khong
	 */
	function payment_format_amount($amount, $payment, $add_currency_info = TRUE)
	{
		// Tai file thanh phan
		$CI	=& get_instance();
		$CI->load->model('payment_model');
		
		// Lay currency cua payment
		$currency = $CI->payment_model->get_currency($payment);
		
		// Them thong tin tien te
		$amount = currency_format_amount($amount, $currency, $add_currency_info);
		
		return $amount;
	}
	
	/**
	 * Xu ly amount theo loai tien te cua payment
	 * @param float		$amount 	So tien (da quy doi sang don vi tien te cua payment)
	 * @param string	$payment	Cong thanh toan
	 * @param bool 		$natural	Co chuyen amount ve so nguyen duong hay khong
	 */
	function payment_handle_amount($amount, $payment, $natural = FALSE)
	{
		// Tai file thanh phan
		$CI	=& get_instance();
		$CI->load->model('payment_model');
		
		// Lay currency cua payment
		$currency_id = $CI->payment_model->get_currency($payment);
		$currency = currency_get_info($currency_id);
		
		// Xu ly amount
		$amount = currency_handle_input($amount, $natural, $currency->decimal);
		
		return $amount;
	}
	
?>