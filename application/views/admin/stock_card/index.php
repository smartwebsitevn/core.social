<?php
	$table = array_only($this->data, ['total', 'actions', 'pages_config', 'orders']);

	$table['filters'] = [

		[
			'param' => 'serial',
			'name'  => lang('card_serial'),
			'value' => array_get($filter, 'serial'),
		],

		[
			'param' => 'code',
			'name'  => lang('card_code'),
			'value' => array_get($filter, 'code'),
		],

		[
			'param'  => 'product_id',
			'name'   => lang('product'),
			'type'   => 'select',
			'value'  => array_get($filter, 'product_id'),
			'values' => $products->lists('name', 'id'),
		],

		[
			'param'  => 'sold',
			'name'   => lang('status'),
			'type'   => 'select',
			'value'  => array_get($filter, 'sold'),
			'values' => [
				'no'  => lang('unsold'),
				'yes' => lang('sold'),
			],
		],

	];
	
	$table['columns'] = [
		'product_id' => lang('product'),
		'serial'     => lang('card_serial'),
		'code'       => lang('card_code'),
		'expire'     => lang('card_expire'),
		'desc'       => lang('desc'),
		'admin_id'   => lang('creator'),
		'sold'       => lang('status'),
		'created'    => lang('created'),
		'action'     => lang('action'),
	];
	
	$rows = [];
	foreach ($list as $row)
	{
		$rows[] = [
			'id'         => $row->id,
			'product_id' => t('html')->a(admin_url('stock_card') . '?product_id=' . $row->product_id, $row->product->name),
			'code'       => $row->code_hidden,
			'serial'     => $row->serial,
			'expire'     => $row->expire,
			'desc'       => $row->desc,
			'admin_id'   => t('html')->a($row->admin->{'adminUrl:view'}, $row->admin->username, ['target' => '_blank']),
			'sold'       => macro()->status_color($row->sold ? 'off' : 'on', lang($row->sold ? 'sold' : 'unsold')),
			'created'    => $row->{'format:created,time'},
			'action'     => macro('mr::table')->actions_data($row, ['view']),
		];
	}
	$table['rows'] = $rows;

	echo macro()->page(array_merge($this->data, [
		'table'   => $table,
		'toolbar' => macro('tpl::stock_card/macros')->toolbar(),
	]));