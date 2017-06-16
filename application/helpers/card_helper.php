<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * ------------------------------------------------------
 *  Main fun
 * ------------------------------------------------------
 */
	/**
	 * Kiem tra su ton tai cua card
	 */
	function card_exists($card)
	{
		if (!$card) return FALSE;
		
		$url = APPPATH.'libraries/card/'.$card.'_card'.EXT;
		
		return (file_exists($url)) ? TRUE : FALSE;
	}
	
	/**
	 * Kiem tra card da duoc cai dat hay chua
	 */
	function card_installed($card)
	{
		if (!$card) return FALSE;
		
		// Tai file thanh phan
		$CI	=& get_instance();
		$CI->load->model('card_model');
		
		// Lay danh sach cac card da cai dat
		$card_installed = $CI->card_model->get_list_installed();
		
		return (in_array($card, $card_installed)) ? TRUE : FALSE;
	}

/*
 * ------------------------------------------------------
 *  Other fun
 * ------------------------------------------------------
 */
	/**
	 * Lay danh sach dau so cua cac provider
	 */
	function card_get_list_pre_phone()
	{
		$list = array();
		$list['mobi'] 		= array('090', '093', '0120', '0121', '0122', '0126', '0128');
		$list['vina'] 		= array('091', '094', '0123', '0124', '0125', '0127', '0129');
		$list['viettel'] 	= array('096', '097', '098', '0162', '0163', '0164', '0165', '0166', '0167', '0168', '0169');
		$list['gmobile'] 	= array('099', '0199');
		$list['beeline'] 	= array('099', '0199');
		$list['vnmobile'] 	= array('092', '0188', '0186');
		
		return $list;
	}
	
	/**
	 * Lay provider tu phone
	 */
	function card_get_provider_from_phone($phone, &$pre_phone = '')
	{
		$phone = preg_replace('#^\+?84#', '0', $phone);
		
		foreach (card_get_list_pre_phone() as $provider => $pre_phones)
		{
			foreach ($pre_phones as $n)
			{
				if (preg_match('#^'.$n.'#', $phone))
				{
					$pre_phone = $n;
					return $provider;
				}
			}
		}
		
		return FALSE;
	}
	
	