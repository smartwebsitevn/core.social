<?php

	$mr = $this->data;

	$mr['form'] = [

		'title' => lang('title_payment_add'),

		'action' => $url_add,

		'attr' => ['method' => 'get', 'id' => ''],

		'rows' => [

			[
				'param'  => 'paygate',
				'type'   => 'select',
				'name'   => lang('select_paygate'),
				'values' => $paygates->lists('name', 'key'),
			],

		],

	];

	echo macro()->page($mr);