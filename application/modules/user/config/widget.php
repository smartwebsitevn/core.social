<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * ------------------------------------------------------
 *  Module Widget Config
 * ------------------------------------------------------
 * 
 * $widget['list']['setting'][param] = (array)options;
 * Danh sach cac tuy chon:
 * 	'type'		= Loai bien. VD: text. Cac loai bien duoc ho tro:
 * 					text, textarea, html, bool, select, select_multi, radio, checkbox, file, image
 * 	'name'		= Tieu de cua bien
 * 	'value'		= Gia tri mac dinh
 * 	'values'	= Cac gia tri tuy chon cua bien, ap dung voi type = select, select_multi, radio, checkbox
 * 					VD: array('value1' => 'Name1', 'value2' => 'Name2', ...)
 */

/*
 * Account panel
 */
$widget['panel'] = array(
	
	'name' => 'Đăng nhập - Đăng kí',
	
	'setting' => array(
		'menu' => array(
			'type' => 'select',
			'name' => 'Menu hiển thị',
		),
	),
		
);

/*
 * User panel
 */
$widget['user_panel'] = [

	'name' => 'Bảng điều khiển',

	'setting' => [
		'menu' => [
			'type' => 'select',
			'name' => 'Menu hiển thị',
		],
	],

];

/*
 * Form login
 */
$widget['login'] = [

	'name' => 'Đăng nhập',

	'setting' => [],

];
