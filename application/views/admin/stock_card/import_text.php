<?php
	$args = $this->data;

	$args['toolbar'] = macro('tpl::stock_card/macros')->toolbar();

	$args['form'] = [

		'title' => lang('title_stock_card_import'),

		'rows' => [

			[
				'param'  => 'product_id',
				'name'   => lang('product'),
				'type'   => 'select',
				'values' => $products->lists('name', 'id'),
			],

			[
				'param' => 'import_text',
				'name'  => lang('list_cards'),
				'type'  => 'textarea',
				'desc'  => lang('help_list_cards'),
			],

			[
				'param'  => 'desc',
				'type'   => 'textarea',
			],

		],

	];

	echo macro()->page($args);