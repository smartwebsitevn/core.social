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
				'param'   => 'import_file',
				'name'    => lang('file'),
				'type'    => 'file',
				'_upload' => $upload_file,
				'desc'    => t('html')->a(public_url('form_import_card.xls'), 'Download file mẫu'),
			],

			[
				'param'  => 'desc',
				'type'   => 'textarea',
			],

		],

	];

	echo macro()->page($args);