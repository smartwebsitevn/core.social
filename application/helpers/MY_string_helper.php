<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	/**
	 * Lay chuoi ki tu bat dau tu ben phai
	 * @param string 	$str	Chuoi dau vao
	 * @param int 		$len	Chieu dai chuoi muon lay
	 */
	function right($str, $len = 1)
	{
	    return substr($str, ($len*-1));
	}
	
	/**
	 * Lay chuoi ki tu bat dau tu ben trai
	 * @param string 	$str	Chuoi dau vao
	 * @param int 		$len	Chieu dai chuoi muon lay
	 */
	function left($string, $len = 1)
	{
	    return substr($string, 0, $len);
	}
	
	/**
	 * Kiem tra su hop le cua phone
	 * @param string $phone
	 */
	function valid_phone($phone)
	{
		// Kiem tra so ki tu
		$phone 	= (string)$phone;
		$len 	= strlen($phone);
		if ($len != 10 && $len != 11)
		{
			return FALSE;
		}
		
		// Kiem tra format
		if (!preg_match('#^0[0-9]+$#', $phone))
		{
			return FALSE;
		}
		
		return TRUE;
	}
	
	/**
	 * Xu ly so dien thoai
	 * @param string $phone
	 */
	function handle_phone($phone)
	{
		$phone = preg_replace('#[^0-9]#', '', $phone); // Loai bo cac ki tu khac so
		$phone = preg_replace('#^\+?84#is', '0', $phone); // Xu ly ma quoc gia (+84)
		
		return $phone;
	}
		/*
 * ------------------------------------------------------
 *  Decode HTML
 * ------------------------------------------------------
 */
	/**
	* Decodes all HTML entities, including numeric and hexadecimal ones.
	*
	* @param mixed $string
	* @return string decoded HTML
	*/
	function html_entity_decode_numeric($string, $quote_style = ENT_COMPAT, $charset = "utf-8")
	{
		$string = html_entity_decode($string, $quote_style, $charset);
		$string = preg_replace_callback('~&#x([0-9a-fA-F]+);~i', "chr_utf8_callback", $string);
		$string = preg_replace('~&#([0-9]+);~e', 'chr_utf8("\\1")', $string);
		return $string;
	}

	/**
	 * Callback helper
	 */
	function chr_utf8_callback($matches)
	{
		return chr_utf8(hexdec($matches[1]));
	}

	/**
	* Multi-byte chr(): Will turn a numeric argument into a UTF-8 string.
	*
	* @param mixed $num
	* @return string
	*/
	function chr_utf8($num)
	{
		if ($num < 128) return chr($num);
		if ($num < 2048) return chr(($num >> 6) + 192) . chr(($num & 63) + 128);
		if ($num < 65536) return chr(($num >> 12) + 224) . chr((($num >> 6) & 63) + 128) . chr(($num & 63) + 128);
		if ($num < 2097152) return chr(($num >> 18) + 240) . chr((($num >> 12) & 63) + 128) . chr((($num >> 6) & 63) + 128) . chr(($num & 63) + 128);
		return '';
	}	
	