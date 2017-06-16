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
	if(!in_array('cat_id',$deny))
		$settings['cat_id'.$k]=[
			'type'   => 'select_multi',
			'name'   => 'danh mục hiển thị',
			'value'  => '',
			'values' => [],
			'desc'   => 'Chọn danh mục muốn hiển thị',
		];
	$settings['sp01'] =  array(
		'type' => 'separate',
		'name' => 'Thiết lập lấy dữ liệu hiển thị tùy chỉnh',
	);
	if(!in_array('level',$deny))
		$settings['level'.$k]=[
			'type'   => 'select',
			'name'   => 'Level tối đa',
			'value'  => '',
			'values' => range(0,5),
		];
	if(!in_array('feature',$deny))
		$settings['feature'.$k]=[
			'type'   => 'select',
			'name'   => 'Chỉ hiển thị danh mục được gán là hấp dẫn',
			'value'  => '',
			'values' => [
				'yes'     => 'Có',
				'no'    => 'Không',
			],
		];

	/*if(!in_array('new',$deny))
		$settings['new'.$k]=[
			'type'   => 'select',
			'name'   => 'Chỉ hiển thị danh mục được gán là mới',
			'value'  => '',
			'values' => [
				'yes'     => 'Có',
				'no'    => 'Không',
			],
		];
	if(!in_array('soon',$deny))
		$settings['soon'.$k]=[
			'type'   => 'select',
			'name'   => 'Chỉ hiển thị danh mục sắp ra mắt',
			'value'  => '',
			'values' => [
				'yes'     => 'Có',
				'no'    => 'Không',
			],
		];*/
	if(!in_array('image',$deny))
		$settings['image'.$k]=[
			'type'   => 'select',
			'name'   => 'Chỉ hiển thị danh mục có ảnh',
			'value'  => '',
			'values' => [
				'yes'     => 'Có',
				'no'    => 'Không',
			],
		];
	if(!in_array('total',$deny))
		$settings['total'.$k]=[
			'type'  => 'text',
			'name'  => 'Số danh mục hiển thị',
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
				//'new'     => 'Mới nhất',
				'updated'     => 'Mới cập nhập',
				//'rate'    => 'Được yêu thích',
				//'view'    => 'Được xem nhiều',
				//'buy'    => 'Được mua nhiều',
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
// List
$widget  ['list']['name'] = 'Danh sách';
$widget  ['list']['setting'] = $_data_list();
$widget  ['list']['setting']['sp1'] =  array(
	'type' => 'separate',
	'name' => 'Thiết lập Layout hiển thị',
);

$widget  ['list']['setting']['style'] = [
	'type' => 'select',
	'name' => 'Style',
	'value' => '',
	'values' => [
		'default' => 'Kiểu hiển thị 1',
		'style1' => 'Kiểu hiển thị 2',
		'style2' => 'Kiểu hiển thị 3',
	],
	'values_opts' => array('value_required' => true),
];