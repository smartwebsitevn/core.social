<?php

	$table = array_only($this->data, ['total', 'actions', 'pages_config', 'orders']);

	//$table['title'] = lang('title_service_order_list');

	$table['filters'] = [

		[
			'param' => 'id',
			'value' => array_get($filter, 'id'),
		],

		[
			'param' => 'key',
			'name'  => lang('key'),
			'value' => array_get($filter, 'key'),
		],

		[
			'param' => 'user_key',
			'name'  => lang('user'),
			'value' => array_get($filter, 'user_key'),
			'attr' => ['title' => 'Có thể nhập username, email, số điện thoại của đại lý'],
		],


		[
			'param'  => 'status',
			'type'   => 'select',
			'name'   => lang('status'),
			'value'  => array_get($filter, 'status'),
			'values' => lang_map($list_service_status, 'service_status_'),
		],
		[
			'param' => 'expire','name' 	=>  lang('expire'), 'type'=> 'select',
			'value' => $filter['expire'],
			'values' => array('yes' => lang('expire_yes'), 'no' => lang('expire_no')),
		],

		[
			'param' => 'expire_to',
			'type'  => 'date',
			'name'  => lang('from_date'),
			'value' => array_get($filter, 'expire_to'),
		],

		[
			'param' => 'expire_to_to',
			'type'  => 'date',
			'name'  => lang('to_date'),
			'value' => array_get($filter, 'expire_to_to'),
		],

	];
	
	$table['columns'] = macro('tpl::service_order/macros')->make_columns();

	$table['rows'] = macro('tpl::service_order/macros')->make_rows($list);


	echo macro()->page(array_merge($this->data, [
		'table' => $table,
		'toolbar' => [],
	]));
	