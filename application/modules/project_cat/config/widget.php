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

// List
$widget['list'] = array(
		
	'name' => 'Danh sách',
		
	'setting' => array(
			'order' => [
					'type' => 'select',
					'name' => 'Sắp xếp theo',
					'value' => 'new',
					'values' => [
							'sort' 	=> 'Thứ tự',
							'az' 		=> 'Theo tên (a->z)',
							'news' 		=> 'Mới nhất',
					],
			],
			'total' => [
					'type' => 'text',
					'name' => 'Số mục hiển thị',
					'value' => 10,
				'desc' => '0 là là không giới hạn'
			],
		'style' => array(
			'type' => 'select',
			'name' => 'Loại hiển thị',
			'values' => [
				'default' => 'Kiểu hiển thị 1',
				'style1' => 'Kiểu hiển thị 2',
				//'style2' => 'Kiểu hiển thị 3',
			],
			'values_opts' => array('value_required' => true),
		),
	),
	
);
