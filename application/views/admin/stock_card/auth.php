<?php
	$args = $this->data;

	$args['form'] = [

		'title' => lang('title_stock_card_auth'),

		'rows' => [

			[
				'param' => 'password',
				'name'  => lang('password'),
				'type'  => 'password',
				'desc'  => 'Nhập mật khẩu đăng nhập admin',
			],

		],

	];

	echo macro()->page($args);