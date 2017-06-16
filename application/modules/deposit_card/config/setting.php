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

$setting = array(
	
	'fail_count_max' => array(
		'type' 	=> 'text',
		'name' 	=> 'Số lần nhập sai thẻ tối đa',
		'desc' 	=> 'Nếu thành viên nhập sai quá số lần cho phép thì sẽ bị block IP. Nếu không khai báo thì không áp dụng',
		'value' => 30,
	),
	
	'fail_block_timeout' => array(
		'type' 	=> 'text',
		'name' 	=> 'Thời gian block IP',
		'desc' 	=> 'Đơn vị là Phút, nếu không khai báo thì block vinh viễn',
		'value' => 30,
	),
	
);
