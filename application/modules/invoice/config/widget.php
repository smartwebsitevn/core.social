<?php

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

$widget = [

	'user_invoice_orders' => [

		'name' => 'Lịch sử đơn hàng của thành viên',

		'setting' => [

			'service_keys' => [
				'type'   => 'select_multi',
				'name'   => 'Loại đơn hàng hiển thị',
				'value'  => '',
				'values' => [],
			],

			'total' => [
				'type'  => 'text',
				'name'  => 'Số đơn hàng hiển thị',
				'value' => 20,
			],

		],

	],

];
