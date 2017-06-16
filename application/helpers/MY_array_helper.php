<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
	/**
	 * Ke thua gia tri giua 2 mang (de quy)
	 * note: Neu arr2 chua key cua arr1 thi value cua key do trong arr1 se co value bang value cua key trong arr2
	 */
	function extend($arr1, $arr2)
	{
		// Neu arr1 khong phai la array hc obj thi tra ve gia tri cua arr2
		$arr1_is_array = is_array($arr1);
		$arr1_is_object = is_object($arr1);
		if ( ! $arr1_is_array && ! $arr1_is_object)
		{
			 return $arr2;
		}
		
		// Gan gia tri cua cac key trung nhau tu arr2 sang arr1
		$arr2_is_array = is_array($arr2);
		$arr2_is_object = is_object($arr2);
		foreach ($arr1 as $k => $v)
		{
			if ($arr1_is_array && $arr2_is_array && isset($arr2[$k]))
			{
				$arr1[$k] = extend($v, $arr2[$k]);
			}
			elseif ($arr1_is_object && $arr2_is_object && isset($arr2->$k))
			{
				$arr1->$k = extend($v, $arr2->$k);
			}
		}
		
		return $arr1;
	}
	
	/**
	 * Loc danh danh cac bien
	 * Lay ra danh sach cac bien yeu cau va khong yeu cau
	 */
	function filter_params($params)
	{
		$list = $req = array();
		foreach ($params as $param)
		{
			if (right($param, 1) == '*')
			{
				$param = str_replace('*', '', $param);
				$req[] = $param;
			}
			$list[] = $param;
		}
		
		$result = array();
		$result['list'] = $list;
		$result['req'] 	= $req;
		
		return $result;
	}
	
	/**
	 * Gan gia tri mac dinh cho key cua bien
	 * @param mix $var		Bien dau vao
	 * @param mix $key		Key muon gan gia tri mac dinh
	 * @param mix $value	Gia tri mac dinh can gan
	 */
	function set_default_value($var, $key, $value = '')
	{
		// Neu $var khong phai la array hoac object
		if ( ! is_array($var) && ! is_object($var))
		{
			return $var;
		}
		
		// Chuyen key thanh array
		$key = ( ! is_array($key)) ? array($key) : $key;
		
		// Gan gia tri
		foreach ($key as $k)
		{
			if (is_array($var))
			{
				$var[$k] = ( ! isset($var[$k])) ? $value : $var[$k];
			}
			elseif (is_object($var))
			{
				$var->$k = ( ! isset($var->$k)) ? $value : $var->$k;
			}
		}
		
		return $var;
	}

function object_to_array($obj) {
    if(is_object($obj)) $obj = (array) $obj;
    if(is_array($obj)) {
		$new = array();
		foreach($obj as $key => $val) {
			$new[$key] = object_to_array($val);
		}
	}
	else $new = $obj;
    return $new;
}// loc bo id trung cua 1 danh sach doi tuong theo 1 key nao do
function array_unique_key($list, $key)
{
	$tmp = array();
	$list = array_filter($list, function ($value) use ($key, &$tmp) {
		if (!is_array($value)) $value = object_to_array($value);
		if (in_array($value[$key], $tmp)) return false;
		$tmp[] = $value[$key];
		return $value[$key];

	});
	return $list;
}

/*
	// sap sep du lieu , du lieu rieng dc day xuong duoi cung
	$list = array_values(array_sort($list, function ($value) {
		return !is_numeric($value['id']);
	}));
*/