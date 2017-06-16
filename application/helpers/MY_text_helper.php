<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


	/**
	 * Kiem tra sau co chua khoang trang
	 */
	function detect_has_space($str) {
		return count(explode(' ', $str)) > 1; 

	}
	/**
	 * Kiem tra sau co chua ki tu UTF8 (unicode)
	 */
	function detect_has_utf8($str) {
		
  	 //header ( "content-type: text/html; charset=utf-8" );
       $characters = array(
			'/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ/' => 'a',
			'/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ/' => 'e',
			'/ì|í|ị|ỉ|ĩ/' => 'i',
			'/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ/' => 'o',
			'/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ/' => 'u',
			'/ỳ|ý|ỵ|ỷ|ỹ/' => 'y',
			'/đ/' => 'd',
			'/À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ/' => 'A',
			'/È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ/' => 'E',
			'/Ì|Í|Ị|Ỉ|Ĩ/' => 'I',
			'/Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ/' => 'O',
			'/Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ/' => 'U',
			'/Ỳ|Ý|Ỵ|Ỷ|Ỹ/' => 'Y',
			'/Đ/' => 'D'
		);
		
		$m =array_keys($characters);
		$m ='#'.implode('', $m).'#';
		//pr($m);
		return preg_match($m,$str);
    
	}
  	/**
	 * Rut gon text theo do dai
	 */
	function character_limiter_len($str, $len)
	{
		if (strlen($str) <= $len)
		{
			return $str;
		}
		
		$str = substr($str, 0, $len - 3).'...';
		
		return $str;
	}

function character_limiter($string,$len=250)
{
	$string= trim($string);
	$string= strip_tags($string);
	$val = _cutText($string, 0, $len);
	return $val[1] ?$val[0]."..." : $val[0];
}
function _cutText($text, $start=0, $limit=12)
{
	if (function_exists('mb_substr')){
		$more = (mb_strlen($text) > $limit) ? TRUE : FALSE;
		$text = mb_substr($text, 0, $limit, 'UTF-8');
		return array($text, $more);
	}else if(function_exists('iconv_substr')){
		$more = (iconv_strlen($text) > $limit) ? TRUE : FALSE;
		$text = iconv_substr($text, 0, $limit, 'UTF-8');
		return array($text, $more);
	}else{
		preg_match_all("/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/", $text, $ar);
		if(func_num_args() >= 3){
			if(count($ar[0])>$limit){
				$more = TRUE;
				$text = join("",array_slice($ar[0],0,$limit))."...";
			}
			$more = TRUE;
			$text = join("",array_slice($ar[0],0,$limit));
		}else{
			$more = FALSE;
			$text = join("",array_slice($ar[0],0));
		}
		return array($text, $more);
	}
}

/**
	 * Chuyen tieng viet co dau sang khong dau
	 */
	function convert_vi_to_en($str)
	{
		$characters = array(
			'/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ/' => 'a',
			'/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ/' => 'e',
			'/ì|í|ị|ỉ|ĩ/' => 'i',
			'/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ/' => 'o',
			'/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ/' => 'u',
			'/ỳ|ý|ỵ|ỷ|ỹ/' => 'y',
			'/đ/' => 'd',
			'/À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ/' => 'A',
			'/È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ/' => 'E',
			'/Ì|Í|Ị|Ỉ|Ĩ/' => 'I',
			'/Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ/' => 'O',
			'/Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ/' => 'U',
			'/Ỳ|Ý|Ỵ|Ỷ|Ỹ/' => 'Y',
			'/Đ/' => 'D'
		);
		
		return url_title(strtolower(preg_replace(array_keys($characters), array_values($characters), $str)));
	}
	
	
	