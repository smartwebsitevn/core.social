<?php
	$args = $this->data;

	$args['form'] = [

		'title' => lang('title_withdraw_admin'),

		'rows' => [

			[
				'param' => 'purse_number',
				'type'  => 'text',
				'desc'  => lang('help_purse_number'),
			],

			[
				'param' => 'amount',
				'name'  => lang('withdraw_amount'),
				'type'  => 'number',
				'desc'  => lang('help_withdraw_amount'),
			],

			[
				'param' => 'desc',
				'name'  => lang('withdraw_reason'),
				'type'  => 'textarea',
			],

		],

	];

	echo macro()->page($args);