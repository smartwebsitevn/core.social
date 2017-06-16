<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * ------------------------------------------------------
 *  Module Setting Params
 * ------------------------------------------------------
 * 
 * $setting[param] = (array)options;
 * Danh sach cac tuy chon:
 * 	'type'			= Loai bien. VD: text. Cac loai bien duoc ho tro:
 * 						text, textarea, html, bool, select, select_multi, radio, checkbox, date, color, file, file_multi, image, image_multi
 * 	'name'			= Tieu de cua bien
 * 	'value'			= Gia tri mac dinh
 * 	'values'		= Cac gia tri tuy chon cua bien, ap dung voi type = select, select_multi, radio, checkbox
 * 						VD: array('value1' => 'Name1', 'value2' => 'Name2', ...)
 * 	'file_allowed'	= Cac loai file cho phep, khong khai bao thi lay theo mac dinh. VD: 'jpg|png'
 * 	'file_private'	= TRUE: File private khong co link down || FALSE: File public co link down
 * 	'file_server'	= TRUE: File luu tren server luu tru (Mac dinh) || FALSE: File luu len server hien tai
 * 	'file_thumb'	= Tao thumb cho anh hay khong, TRUE || FALSE
 */

$setting = [];
//
/*$p = array();
$p['type'] 		= 'html';
$p['name'] 		= 'Thông báo';
$p['value'] 	= '';
$setting['notice'] = $p;*/

$p = array();
$p['type'] 		= 'text';
$p['name'] 		= 'Google Map X - ';
$p['desc'] 	= 'Tọa độ X trên bản đồ';
$setting['google_map_x'] = $p;
$p = array();
$p['type'] 		= 'text';
$p['name'] 		= 'Google Map Y';
$p['desc'] 	=   'Tọa độ Y trên bản đồ - '.t('html')->a('http://map.google.com' ,'Chọn tọa độ trên Google Map' ,array('target'=>'_blank'));
$setting['google_map_y'] = $p;

$p = array();
$p['type'] 		= 'text';
$p['name'] 		= 'Google API Key';
$p['value'] 	= '';
$setting['google_api_key'] = $p;

//$p = array();
//$p['type'] 		= 'image';
//$p['name'] 		= 'Logo';
//$setting['logo'] = $p;
//foreach (array('yahoo', 'skype', 'facebook', 'twitter', 'google', 'youtube') as $v)
//{
//	$p = array();
//	$p['type'] 		= 'text';
//	$p['name'] 		= ucfirst($v);
//	$p['value'] 	= $v;
//	$setting[$v] = $p;
//}
