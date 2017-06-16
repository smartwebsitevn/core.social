<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * ------------------------------------------------------
 *  Module Table Config
 * ------------------------------------------------------
 * 
 * $table[table][name] 		= Ten table;
 * $table[table][cols][col] 	= (array)options;
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
 * 	'show'			= Hien thi column khi list trong admin hay khong
 */

// providers_mobile
$table['offline_providers'] = array(
	
	'name' => 'Nhà cung cấp thẻ',
	
	'cols' => array(
	
		'name' => array(
			'type' => 'text',
			'name' => 'Tên',
		),
	
		'discount' => array(
			'type' => 'text',
			'name' => 'Chiết khấu (%)',
		),
	
		'order' => array(
			'type' => 'text',
			'name' => 'Số thứ tự',
		),
	
	),
		
);