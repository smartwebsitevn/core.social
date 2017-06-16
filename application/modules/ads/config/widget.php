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
			'name'   => 'banner hiển thị',
			'value'  => '',
			'values' => [],
			'desc'   => 'Chọn banner muốn hiển thị',
		];
	$settings['sp01'] =  array(
		'type' => 'separate',
		'name' => 'Thiết lập lấy dữ liệu hiển thị tùy chỉnh',
	);
	if(!in_array('location_id',$deny))
		$settings['location_id'.$k]=[
			//'type'   => 'select',
			'type'   => 'select_multi',
			'name'   => 'Vị trí',
			'value'  => '',
			'values' => [],
			'desc'   => 'Nếu không khai báo thì sẽ hiển thị tất cả',
		];
	if(!in_array('total',$deny))
		$settings['total'.$k]=[
			'type'  => 'text',
			'name'  => 'Số banner hiển thị',
			'value' => 10,
		];
	if(!in_array('order',$deny))
		$settings['order'.$k]=[
			'type'   => 'select',
			'name'   => 'Sắp xếp theo',
			'value'  => 'new',
			'values' => [
				'name' 		=> 'Theo tên (a->z)',
				'new'     => 'Mới nhất',
				'view'    => 'Được xem nhiều',
				'click'    => 'Được click nhiều',
				'random'  => 'Random'
			]
		];

/*	if(!in_array('url_more',$deny))
		$settings['url_more'.$k]=[
			'type'  => 'text',
			'name'  => 'Link xem thêm',
		];*/

	return $settings;
};

$widget = [];
//================ List
$widget  ['list']['name'] = 'Danh sách banner';
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
		'default' 	=> 'Default',
		'content'	=> 'Hiển thị ở nội dung',
		'top' 	=> 'Hiển thị ở Top',
	],
	'values_opts' => array('value_required' => true),
];
