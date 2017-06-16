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


/*
 * Info
 */
/*$widget['info'] = array(
	'name' => 'Info',
	'setting' => array(
		'image' => array('type' => 'image', 'name' => 'Ảnh',),
		'title' => array('type' => 'html', 'name' => 'Tiêu đề',),
		'intro' => array('type' => 'html', 'name' => 'Giới thiệu',),
		'link' => array('type' => 'text', 'name' => 'Liên kết',),

	),
);*/
/*
 * Header
 */
$widget['header'] = array(

    'name' => 'Header',

    'setting' => array(
        'menu' => array(
            'type' => 'select',
            'name' => 'Menu hiển thị',
        ),
        'hotline' => array('type' => 'text', 'name' => 'Hotline',),
        //'time_work' => array('type' => 'text','name' => 'Thời gian làm việc',	),
    ),
);
/*
 * Footer
 */
$widget['footer'] = array(
    'name' => 'Footer',
    'setting' => array(),
);
$widget['footer']["setting"]['sp_h1'] = ['type' => 'separate', 'name' => 'Menu hiển thị '];
for ($i = 1; $i <= 3; $i++) {
    $widget['footer']["setting"]['menu' . $i] = ['type' => 'select', 'name' => 'Menu ' . $i];
    //$widget['footer']["setting"]['name'.$i]=['type' => 'text',	'name' => 'Tên '];

}
//$widget['footer']["setting"]['sp_f1'] = ['type' => 'separate'];
/*for($i=1;$i<=3;$i++){
	$widget['footer']["setting"]['sp_image_'.$i]=['type' => 'separate',	'name' => 'Ảnh '.$i];
	$widget['footer']["setting"]['link'.$i]=['type' => 'text',	'name' => 'Link '];
	$widget['footer']["setting"]['image'.$i]=['type' => 'image',	'name' => 'Ảnh '];
	//$widget['footer']["setting"]['name'.$i]=['type' => 'text',	'name' => 'Tên '];

}*/
$widget['footer']["setting"]['sp_f1'] = ['type' => 'separate'];
//$widget['footer']["setting"]['intro'] = ['type' => 'html', 'name' => 'Intro'];
$widget['footer']["setting"]['copyright'] = ['type' => 'html', 'name' => 'Copyright'];
//$widget['footer']["setting"]['js'] = ['type' => 'textarea', 'name' => 'Javascript code'];

/*
 * Menu
 */
$widget['menu'] = array(

    'name' => 'Menu',

    'setting' => array(

        'menu' => array(
            'type' => 'select',
            'name' => 'Menu hiển thị',
        ),

        'type' => array(
            'type' => 'select',
            'name' => 'Kiểu menu',
            'value' => 'top',
            'values' => array(
                'top' => 'Top',
                'service' => 'Service',
                'footer' => 'Footer',
            ),
        ),

    ),
);


/*
 * Slider
 */
$widget['slider'] = array(

    'name' => 'Slider',

    'setting' => array(

        'slider' => array(
            'type' => 'select',
            'name' => 'Slider hiển thị',
        ),

    ),
);


/*
 * Services
 */
/*$widget['services'] = array(

	'name' => 'Dịch vụ',

	'setting' => array(

	),
);
$widget['services']["setting"]['header']=['type' => 'html',	'name' => 'Giới thiệu'];

for($i=1;$i<=4;$i++){
	$widget['services']["setting"]['sp'.$i]=['type' => 'separate',	'name' => 'Dịch vụ '.$i];
	$widget['services']["setting"]['link'.$i]=['type' => 'text',	'name' => 'Link '];
	$widget['services']["setting"]['image'.$i]=['type' => 'image',	'name' => 'Ảnh '];
	$widget['services']["setting"]['name'.$i]=['type' => 'text',	'name' => 'Tên '];

}
$widget['services']["setting"]['footer']=['type' => 'html',	'name' => 'Footer'];*/


/*
 * Html
 */
$widget['html'] = array(

    'name' => 'HTML',

    'setting' => array(

        'box' => array(
            'type' => 'bool',
            'name' => 'Sử dụng Box',
        ),

        'content' => array(
            'type' => 'html',
            'name' => 'Nội dung hiển thị',
            'translate' => 1,

        ),

        'class' => array(
            'type' => 'text',
            'name' => 'Css class',
        ),

    ),
);


/*
 * Html tab
 */
/*
$widget['html_tab'] = array(

	'name' => 'HTML Dạng Tab',

	'setting' => array(
		'content_top' => array(
			'type' => 'html',
			'name' => 'Nội dung trên',
		),

	),
);
//$widget['html_tab']["setting"]['sp_top']=['type' => 'separate'];
for($i=1;$i<=5;$i++){
	$widget['html_tab']["setting"]['sp'.$i]=['type' => 'separate',	'name' => 'Tab '.$i];
	$widget['html_tab']["setting"]['name'.$i]=['type' => 'text',	'name' => 'Tên '];
	$widget['html_tab']["setting"]['image'.$i]=['type' => 'image',	'name' => 'Ảnh '];
	$widget['html_tab']["setting"]['content'.$i]=['type' => 'html',	'name' => 'Nội dung '];


}
$widget['html_tab']["setting"]['sp_bottom']=['type' => 'separate'];
$widget['html_tab']["setting"]['content_bottom']=['type' => 'html',	'name' => 'Nội dung dưới'];*/


/*
 * Contact
 */
$widget['contact'] = array(
    'name' => 'Liên hệ',
    'setting' => array(
        'intro' => [
            'type' => 'html',
            'name' => 'Mô tả',
        ],

    ),
);


/*
 * Support
 */
/*
$widget['support'] = array(

	'name' => 'Hỗ trợ',

	'setting' => array(),

);*/

/*
 * Support
 */
/*$widget['question_answer'] = array(

	'name' => 'Hỏi đáp',

	'setting' => array(
		'total' => [
			'type' => 'text',
			'name' => 'Số tin hiển thị',
			'value' => 10,
		],

	),

);*/

$widget['ads_location'] = array(
    'name' => 'Danh sách Banner',
    'setting' => array(
        'location_id' => array(
            'type' => 'select',
            'name' => 'Loại hiển thị',
            'values_opts' => array('value_required' => true),
        ),
		'content_top' => ['type' => 'html', 'name' => 'Mô tả trên'],
		'content_bottom' => ['type' => 'html', 'name' => 'Mô tả dưới'],
        'style' => array(
            'type' => 'select',
            'name' => 'Loại hiển thị',
            'values' => [
                'style1' => 'Kiểu hiển thị 1',
                'style2' => 'Kiểu hiển thị 2',
                'style3' => 'Kiểu hiển thị 3',
            ],
            'values_opts' => array('value_required' => true),
        ),
    ),

);

$widget['ads_banner'] = array(
	'name' => 'Banner quảng cáo',
	'setting' => array(
		/*'banner_id' => array(
			'type' => 'select',
			'name' => 'Banner hiển thị',
		),*/
		'banner_id' => array(
			'type' => 'select_multi',
			'name' => 'Banner hiển thị',
			'values' =>[],
		),
		'premium' => array(
			'type' => 'bool',
			'name' => 'Chỉ hiển thị khi tải khoản chưa đăng ký dịch vụ',
		),

		 'type' => array(
             'type' => 'select',
             'name' => 'Hiển thị tại',
             'value' => 'default',
             'values' => array(
                 'content'	=> 'Hiển thị ở nội dung',
                 'scroll' 	=> 'Hiển thị ở Sidebar',
             ),
         ),

	),

);
/*
 * Block
 */
$widget['block'] = array(

    'name' => 'Khối hiển thị',

    'setting' => array(
        'image' => ['type' => 'image', 'name' => 'Hình ảnh'],
        'image_align' => [
            'type' => 'select', 'name' => 'Hình ảnh hiển thị',
            'value' => 'left',
            'values' => array(
				'left' => 'Hiển thị bên trái nội dung',
				'right' => 'Hiển thị bên phải nội dung',
				'background' => 'Hiển thị làm ảnh nền',
            ),
        ],
        'link' => ['type' => 'text', 'name' => 'Liên kết'],
        'title' => ['type' => 'html', 'name' => 'Tiêu đề'],
        'content' => ['type' => 'html', 'name' => 'Nội dung '],
		'container_wraper' => array(
			'type' => 'select',
			'name' => 'Loại hiển thị',
			'values' => [
				'' => 'Full',
				'container' => 'Trong nội dung',
			],
			'values_opts' => array('value_required' => true),
		),
		'container_color' => ['type' => 'text', 'name' => 'Màu nền'],

		/*'style' => array(
			'type' => 'select',
			'name' => 'Loại hiển thị',
			'values' => [
				'style1' => 'Kiểu hiển thị 1',
				'style2' => 'Kiểu hiển thị 2',
				'style3' => 'Kiểu hiển thị 3',
			],
			'values_opts' => array('value_required' => true),
		),*/
    ),
);
/*
 * Partner
 */
/*$widget['partner'] = array(
	
	'name' => 'Đối tác',
	
	'setting' => array(
		
		'list' => array(
			'type' => 'image_multi',
			'name' => 'Danh sách hình ảnh',
		),
		
	),
	
);*/


/*
 * Notify
 */
/*$widget['notify'] = array(

	'name' => 'Thông báo',

	'setting' => array(

		'content' => array(
			'type' => 'textarea',
			'name' => 'Nội dung',
			'desc' => 'Mỗi dòng tương ứng với 1 thông báo',
		),

	),
);*/

// Lang
/*$widget['lang'] = array(
	'name' => 'Ngôn ngữ',
	'setting' => array(),
);*/

/*
 * Social
 */
/*$widget['social'] = array(

	'name' => 'Mạng xã hội',

	'setting' => array(),

);*/


/*
 * Google translate
 */
/*$widget['ggt'] = array(

	'name' => 'Google translate',

	'setting' => array(),

);

$_ = array(
	'vi' => 'Việt nam',
	'en' => 'English',
	'zh-TW' => 'Đài loan',
	'zh-CN' => 'Trung quốc',
	'ja' => 'Nhật bản',
	'ko' => 'Hàn quốc',
);

foreach ($_ as $k => $v)
{
	$widget['ggt']['setting']['img_'.$k] = array(
		'type' => 'image',
		'name' => 'Quốc kì '.$v,
	);
}*/

/*
 * Facebook
 */
/*
$widget['facebook'] = array(

	'name' => 'Facebook Fanpage',

	'setting' => array(

		'href' => array(
			'type' => 'text',
			'name' => 'Facebook Page URL',
			'value' => 'https://www.facebook.com/FacebookDevelopers',
		),

		'width' => array(
			'type' => 'text',
			'name' => 'Width',
		),

		'height' => array(
			'type' => 'text',
			'name' => 'Height',
		),

		'colorscheme' => array(
			'type' => 'select',
			'name' => 'Color Scheme',
			'value' => 'light',
			'values' => array(
				'light' => 'Light',
				'dark' 	=> 'Dark',
			),
		),

		'show-faces' => array(
			'type' => 'bool',
			'name' => 'Show Friends\' Faces',
			'value' => true,
		),

		'header' => array(
			'type' => 'bool',
			'name' => 'Show Header',
			'value' => false,
		),

		'stream' => array(
			'type' => 'bool',
			'name' => 'Show Posts',
			'value' => false,
		),

		'show-border' => array(
			'type' => 'bool',
			'name' => 'Show Border',
			'value' => false,
		),

	),

);

*/
/*$widget['tab'] = [
	'name'    => 'Tab',
	'setting' => []
];*/