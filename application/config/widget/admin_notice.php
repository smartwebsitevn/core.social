<?php

$config = [
	'menu'            => [
		'notice'        => [
			'notice','notice_cat',
			//'notice_to_subscribe','notice_to_report',  'notice_request',
			'notice_setting',
		],

	],
	'menu_url'        => [
		'notice' => [
			'notice_setting' => admin_url('module/setting/notice'),
		],
	],

];