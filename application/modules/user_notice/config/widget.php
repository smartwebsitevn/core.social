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
$_data_list =function($k='',$deny=array()){
	$settings =[];
	$settings['sp00'] =  array(
		'type' => 'separate',
		'name' => 'Thiết lập lấy dữ liệu hiển thị cố định',
	);
	if(!in_array('id',$deny))
		$settings['id'.$k]=[
			'type'   => 'select_multi',
			'name'   => 'sản phẩm hiển thị',
			'value'  => '',
			'values' => [],
			'desc'   => 'Chọn user_notice muốn hiển thị',
		];
	$settings['sp01'] =  array(
		'type' => 'separate',
		'name' => 'Thiết lập lấy dữ liệu hiển thị tùy chỉnh',
	);
	if(!in_array('cat_id',$deny))
		$settings['cat_id'.$k]=[
			//'type'   => 'select',
			'type'   => 'select_multi',
			'name'   => 'Danh mục',
			'value'  => '',
			'values' => [],
			'desc'   => 'Nếu không khai báo thì sẽ hiển thị tất cả',
		];
	if(!in_array('price',$deny))
		$settings['price'.$k]=[
			'type' => 'select',
			'name' => 'Giá',
			'value' => '-1',
			'values' => [
				-1 => 'Tất cả',
				0 => 'Liên hệ',
				1 => 'Có giá',
			],
			'desc'   => 'Nếu không khai báo thì sẽ hiển thị tất cả',
		];
	if(!in_array('feature',$deny))
		$settings['feature'.$k]=[
			'type'   => 'select',
			'name'   => 'Chỉ hiển thị user_notice được gán là hấp dẫn',
			'value'  => '',
			'values' => [
				'yes'     => 'Có',
				'no'    => 'Không',
			],
		];

	if(!in_array('new',$deny))
        $settings['new'.$k]=[
            'type'   => 'select',
            'name'   => 'Chỉ hiển thị user_notice được gán là mới',
            'value'  => '',
            'values' => [
                'yes'     => 'Có',
                'no'    => 'Không',
            ],
        ];
	if(!in_array('soon',$deny))
		$settings['soon'.$k]=[
			'type'   => 'select',
			'name'   => 'Chỉ hiển thị user_notice sắp ra mắt',
			'value'  => '',
			'values' => [
				'yes'     => 'Có',
				'no'    => 'Không',
			],
		];
	if(!in_array('image',$deny))
		$settings['image'.$k]=[
			'type'   => 'select',
			'name'   => 'Chỉ hiển thị user_notice có ảnh',
			'value'  => '',
			'values' => [
				'yes'     => 'Có',
				'no'    => 'Không',
			],
		];
	if(!in_array('total',$deny))
		$settings['total'.$k]=[
			'type'  => 'text',
			'name'  => 'Số user_notice hiển thị',
			'value' => 10,
		];
	if(!in_array('order',$deny))
		$settings['order'.$k]=[
			'type'   => 'select',
			'name'   => 'Sắp xếp theo',
			'value'  => 'new',
			'values' => [
				'name' 		=> 'Theo tên (a->z)',
				'feature' => 'Nổi bật',
				'new'     => 'Mới nhất',
				'updated'     => 'Mới cập nhập',
                'rate'    => 'Được yêu thích',
				'view'    => 'Được xem nhiều',
				'buy'    => 'Được mua nhiều',
				'random'  => 'Random'
			]
		];

	if(!in_array('url_more',$deny))
		$settings['url_more'.$k]=[
			'type'  => 'text',
			'name'  => 'Link more',
		];

	return $settings;
};

$widget = [];
//================ List
$widget  ['list']['name'] = 'Danh sách user_notice';
$widget  ['list']['setting'] = $_data_list();
$widget  ['list']['setting']['sp1'] =  array(
	'type' => 'separate',
	'name' => 'Thiết lập Layout hiển thị',
);
/*$widget  ['list']['setting']['layout_id'] =[
        'type'   => 'select_multi',
        'name'   => 'user_notice đặc biệt',
        'value'  => '',
        'values' => [],
    ];*/

$widget  ['list']['setting']['style'] = [
	'type' => 'select',
	'name' => 'Style',
	'value' => '',
	'values' => [
		'page_home' => 'Hiển thị ở trang chủ: style content',//: style 1
		'page_item' => 'Hiển thị ở trang chủ: style top',
		/*'page_home3' => 'Hiển thị ở trang chủ: style 3',
		'page_home4' => 'Hiển thị ở trang chủ: style 4',
		'page_product' => 'Hiển thị ở trang user_notice',*/
	],
	'values_opts' => array('value_required' => true),
];
//================ List fix
$widget  ['list_fixed']['name'] = 'Danh sách user_notice tùy chọn';

for($i=1;$i<=3;$i++){
	$widget['list_fixed']["setting"]['sp'.$i]=['type' => 'separate',	'name' => 'Danh sách user_notice '.$i];
	$widget['list_fixed']["setting"]['name'.$i]=['type' => 'text',	'name' => 'Tiêu đề '];
	$widget['list_fixed']['setting']['id'.$i] =[
		'type'   => 'select_multi',
		'name'   => 'Danh sách user_notice ',
		'value'  => '',
		'values' => [],
	];
}
$widget['list_fixed']["setting"]['sp10']=['type' => 'separate'];
$widget  ['list_fixed']['setting']['style'] = [
	'type' => 'select',
	'name' => 'Style',
	'value' => '',
	'values' => [
		'page_home_fixed' => 'Mặc định',
	],
	'values_opts' => array('value_required' => true),
];
