<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
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

    'list' => [

        'name' => 'Danh sách combo',

        'setting' => [

            'feature' => [
                'type' => 'select',
                'name' => 'Chỉ hiển thị Combo được gán là hấp dẫn',
                'value' => '',
                'values' => [
                    'yes' => 'Có',
                    'no' => 'Không',
                ],
            ],
            'image' => [
                'type' => 'select',
                'name' => 'Chỉ hiển thị Combo có ảnh',
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
                  //  'feature' => 'Nổi bật',
                    'new' => 'Mới nhất',
                    'sort' 	=> 'Thứ tự',
                    'name' 		=> 'Theo tên (a->z)',
                    'random' => 'Random',
                ],
            ],

            'total' => [
                'type' => 'text',
                'name' => 'Số Combo hiển thị',
                'value' => 3,
            ],
            'url_more' => [
                'type'  => 'text',
                'name'  => 'Link more',
            ],
        ],

    ],


];
