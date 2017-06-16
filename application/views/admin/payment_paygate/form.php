<?php
	$args = $this->data;

	$args['form'] = [

		'title' => $title,

		'data' => $paygate->toArray(),

		'rows' => [

			[
				'param' => 'name',
				'type'  => 'text',
			],

			[
				'param' => 'desc',
				'type'  => 'textarea',
			],

			[
				'param' => 'status',
				'type'  => 'bool',
			],

		],

	];

	echo macro()->page($args);