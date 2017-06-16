<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// List
/*$widget['list'] = [

	'name' => 'Danh sách tin tức',

	'setting' => [

		'cat' => [
			'type' => 'select_multi',
			'name' => 'Thể loại',
			'value' => '',
			'values' => [],
			'desc' => 'Nếu không khai báo thì sẽ hiển thị tất cả',
		],
		'feature' => [
			'type' => 'select',
			'name' => 'Chỉ hiển thị Tin tức nổi bật',
			'value' => '',
			'values' => [
				'yes' => 'Có',
				'no' => 'Không',
			],
		],
		'image' => [
			'type' => 'select',
			'name' => 'Chỉ hiển thị Tin tức có ảnh',
			'value' => '',
			'values' => [
				'yes' => 'Có',
				'no' => 'Không',
			],
		],
		'order' => [
			'type' => 'select',
			'name' => 'Sắp xếp theo',
			'value' => 'new',
			'values' => [
				'feature' 	=> 'Nổi bật',
				'new' 		=> 'Mới nhất',
				'view' 		=> 'Xem nhiều',
				'random' 	=> 'Random',
			],
		],
		'total' => [
			'type' => 'text',
			'name' => 'Số tin tức hiển thị',
			'value' => 16,
		],
		'style' => [
			'type' => 'select',
			'name' => 'Kiểu hiển thị',
			'value' => 'new',
			'values' => [
				'page_default' 		=> 'Mặc định',
				'page_home' 		=> 'Trang chủ',
			],
		],
	],

];*/

// Cat
/*$widget['cat'] = [

	'name' => 'Danh sách thể loại tin',

	'setting' => [],

];*/

// cats_news
$widget['cats_news'] = [

	'name' => 'Danh sách tin',

	'setting' => [

		'cat' => [
			'type' => 'select_multi',
			'name' => 'Thể loại',
			'value' => '',
			'values' => [],
			'desc' => 'Nếu không khai báo thì sẽ hiển thị tất cả',
		],
		'feature' => [
			'type' => 'select',
			'name' => 'Chỉ hiển thị Tin tức nổi bật',
			'value' => '',
			'values' => [
				'yes' => 'Có',
				'no' => 'Không',
			],
		],
		'image' => [
			'type' => 'select',
			'name' => 'Chỉ hiển thị Tin tức có ảnh',
			'value' => '',
			'values' => [
				'yes' => 'Có',
				'no' => 'Không',
			],
		],
		'order' => [
			'type' => 'select',
			'name' => 'Sắp xếp tin tức theo',
			'value' => 'new',
			'values' => [
				'feature' 	=> 'Nổi bật',
				'new' 		=> 'Mới nhất',
				'view' 		=> 'Xem nhiều',
				'random' 	=> 'Random',
			],
		],

		'total' => [
			'type' => 'text',
			'name' => 'Số tin tức hiển thị',
			'value' => 4,
		],
		'style' => [
			'type' => 'select',
			'name' => 'Giao diện',
			'value' => 'new',
			'values' => [
				'news' 	=> 'Tin tức',
				'question' 	=> 'Câu hỏi',
			],
		],
	],

];
// cats_tab
/*$widget['cats_tab'] = [

	'name' => 'Danh sách tin dạng Tab',

	'setting' => [

		'cat' => [
			'type' => 'select_multi',
			'name' => 'Thể loại',
			'value' => '',
			'values' => [],
		],
		'feature' => [
			'type' => 'select',
			'name' => 'Chỉ hiển thị Tin tức nổi bật',
			'value' => '',
			'values' => [
				'yes' => 'Có',
				'no' => 'Không',
			],
		],
		'image' => [
			'type' => 'select',
			'name' => 'Chỉ hiển thị Tin tức có ảnh',
			'value' => '',
			'values' => [
				'yes' => 'Có',
				'no' => 'Không',
			],
		],
		'order' => [
			'type' => 'select',
			'name' => 'Sắp xếp tin tức theo',
			'value' => 'new',
			'values' => [
				'feature' 	=> 'Nổi bật',
				'new' 		=> 'Mới nhất',
				'view' 		=> 'Xem nhiều',
				'random' 	=> 'Random',
			],
		],

		'total' => [
			'type' => 'text',
			'name' => 'Số tin tức hiển thị',
			'value' => 4,
		],
		'style' => [
			'type' => 'select',
			'name' => 'Giao diện',
			'value' => 'new',
			'values' => [
				'default' 	=> 'Mặc định',
			],
		],
	],

];*/