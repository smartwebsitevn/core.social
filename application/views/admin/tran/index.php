<?php
	$table = array_only($this->data, ['total', 'actions', 'pages_config', 'orders']);

	$table['title'] = lang('title_tran_list');

	$table['filters'] = [

		[
			'param' => 'id',
			'value' => array_get($filter, 'id'),
		],

		[
			'param'  => 'status',
			'type'   => 'select',
			'name'   => lang('status'),
			'value'  => array_get($filter, 'status'),
			'values' => lang_map($list_status, 'tran_status_'),
		],

		[
			'param' => 'user_key',
			'name'  => lang('user'),
			'value' => array_get($filter, 'user_key'),
			'attr' => ['title' => 'Có thể nhập username, email, số điện thoại của thành viên'],
		],

		[
			'param' => 'user_ip',
			'name'  => 'IP',
			'value' => array_get($filter, 'user_ip'),
		],

		[
			'param' => 'created',
			'type'  => 'date',
			'name'  => lang('from_date'),
			'value' => array_get($filter, 'created'),
		],

		[
			'param' => 'created_to',
			'type'  => 'date',
			'name'  => lang('to_date'),
			'value' => array_get($filter, 'created_to'),
		],

	];
	
	$table['columns'] = macro('tpl::tran/macros')->make_columns();

	$table['rows'] = macro('tpl::tran/macros')->make_rows($list);

	$table['stats'] = [
		'Tổng số tiền' => currency_format_amount_default($list->sum('amount')),
	];

	echo macro()->page(array_merge($this->data, [
		'table' => $table,
		'toolbar' => [],
	]));