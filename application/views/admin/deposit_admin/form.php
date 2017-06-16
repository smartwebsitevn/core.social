<?php
	$args = $this->data;

	$args['form'] = [

		'title' => lang('title_deposit_admin'),

		'rows' => [

			[
				'param' => 'purse_number',
				'type'  => 'text',
				'desc'  => lang('help_purse_number'),
			],

			[
				'param' => 'amount',
				'name'  => lang('deposit_amount'),
				'type'  => 'number',
				'desc'  => lang('help_deposit_amount'),
			],

			[
				'param' => 'desc',
				'name'  => lang('deposit_reason'),
				'type'  => 'textarea',
			],

		],

	];

	echo macro()->page($args);